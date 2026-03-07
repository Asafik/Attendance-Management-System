/**
 * Table Handler - Menangani sorting dan searching untuk semua tabel
 */

const TableHandler = (function() {
    const tables = {};

    function init(tableId, options = {}) {
        const defaultOptions = {
            searchInputId: `${tableId}-search`,
            sortableColumns: [0, 1],
            defaultSortColumn: 0,
            defaultSortOrder: 'asc',
            onDataChange: null // callback ketika data berubah
        };

        const config = { ...defaultOptions, ...options };

        const $table = $(`#${tableId}`);
        if (!$table.length) {
            console.error(`Table not found: #${tableId}`);
            return;
        }

        const $tbody = $table.find('tbody');
        if (!$tbody.length) return;

        tables[tableId] = {
            config: config,
            originalData: [],
            displayData: [],
            sortColumn: config.defaultSortColumn,
            sortOrder: config.defaultSortOrder,
            searchTerm: '',
            $table: $table,
            $tbody: $tbody,
            $searchInput: $(`#${config.searchInputId}`)
        };

        const table = tables[tableId];
        loadOriginalData(table);
        setupEventListeners(table);

        // Initial sort
        sortTable(table, config.defaultSortColumn);

        return table;
    }

    function loadOriginalData(table) {
        const { $tbody } = table;
        table.originalData = [];

        $tbody.find('tr').each(function(index) {
            const $row = $(this);
            if ($row.hasClass('no-data-row')) return;

            const rowData = {
                id: $row.data('id') || index + 1,
                name: $row.data('name') || $row.find('td:eq(1)').text().trim(),
                cells: [],
                element: $row,
                index: index
            };

            $row.find('td').each(function(cellIndex) {
                const $cell = $(this);
                // Skip kolom aksi (biasanya kolom terakhir)
                if ($cell.find('.action-btns').length) {
                    rowData.hasActions = true;
                } else {
                    rowData.cells.push({
                        index: cellIndex,
                        text: $cell.text().trim(),
                        numeric: !isNaN(parseFloat($cell.text())) && !isNaN($cell.text() - 0)
                    });
                }
            });

            table.originalData.push(rowData);
        });
    }

    function setupEventListeners(table) {
        const { $searchInput, config } = table;

        // Search input
        if ($searchInput.length) {
            $searchInput.off('keyup').on('keyup', debounce(function() {
                table.searchTerm = $(this).val();
                filterData(table);
            }, 300));
        }

        // Sortable headers
        config.sortableColumns.forEach(colIndex => {
            const $header = $(`#${table.$table.attr('id')} thead th:eq(${colIndex})`);
            $header.off('click').on('click', function() {
                sortTable(table, colIndex);
            }).css('cursor', 'pointer');
        });
    }

    function filterData(table) {
        const { originalData, searchTerm } = table;

        let filtered = originalData;

        // Apply search filter
        if (searchTerm) {
            const term = searchTerm.toLowerCase();
            filtered = originalData.filter(item =>
                item.name.toLowerCase().includes(term)
            );
        }

        table.displayData = filtered;

        // Trigger callback if exists
        if (table.config.onDataChange) {
            table.config.onDataChange(filtered);
        }

        updateSortIcons(table);
    }

    function sortTable(table, column) {
        if (table.sortColumn === column) {
            table.sortOrder = table.sortOrder === 'asc' ? 'desc' : 'asc';
        } else {
            table.sortColumn = column;
            table.sortOrder = 'asc';
        }

        // Sort original data
        table.originalData.sort((a, b) => {
            let valA, valB;

            if (column === 0) {
                valA = a.index + 1;
                valB = b.index + 1;
            } else {
                valA = a.name.toLowerCase();
                valB = b.name.toLowerCase();
            }

            if (table.sortOrder === 'asc') {
                return valA < valB ? -1 : valA > valB ? 1 : 0;
            } else {
                return valA > valB ? -1 : valA < valB ? 1 : 0;
            }
        });

        // Re-filter after sort
        filterData(table);
        updateSortIcons(table);
    }

    function updateSortIcons(table) {
        const { sortColumn, sortOrder, $table } = table;

        $table.find('thead th i.sort-icon').removeClass('bi-arrow-up bi-arrow-down').addClass('bi-arrow-down-up');
        $table.find('thead th').removeClass('active-sort');

        const sortIcon = sortOrder === 'asc' ? 'bi-arrow-up' : 'bi-arrow-down';
        $table.find(`thead th:eq(${sortColumn}) i.sort-icon`).removeClass('bi-arrow-down-up').addClass(sortIcon);
        $table.find(`thead th:eq(${sortColumn})`).addClass('active-sort');
    }

    function getDisplayData(tableId) {
        const table = tables[tableId];
        return table ? table.displayData : [];
    }

    function debounce(func, wait) {
        let timeout;
        return function(...args) {
            clearTimeout(timeout);
            timeout = setTimeout(() => func.apply(this, args), wait);
        };
    }

    return {
        init,
        getDisplayData
    };
})();

window.TableHandler = TableHandler;
