<template>
    <div class="data-table">
        <div class="form-group row mx-0">
            <div class="col-sm-6 col-md-7"></div>
            <label class="col-form-label font-weight-bold pt-2 col-sm-2 text-right" for="search" placeholder="Service name">Search</label>
            <div class="col-sm-4 col-md-3">
                <input type="text" id="search" class="form-control form-control-sm" v-model="search">
            </div>
        </div>
        <table class="table">
            <thead>
                <tr>
                    <th class="table-head">#</th>
                    <th v-for="column in columns" :key="column" class="table-head" @click="sortByColumn(column)">
                        {{ column | columnHead }}
                        <span v-if="column === sortedColumn">
                            <i v-if="order === 'asc' " class="fas fa-arrow-up"></i>
                            <i v-else class="fas fa-arrow-down"></i>
                        </span>
                    </th>
                    <th class="table-head"></th>
                </tr>
            </thead>
            <tbody>
                <tr class v-if="tableData.length === 0">
                    <td class="lead text-center" :colspan="columns.length + 1">No data found.</td>
                </tr>
                <tr v-for="data in tableData" :key="data.id" class="m-datatable__row" v-else>
                    <td v-for="(value, key) in data" v-bind:key="key">
                        {{ value }}
                    </td>
                    <td>
                        <form method="POST" :action="deleteUrl + '/'  + data.id" accept-charset="UTF-8">
                            <input name="_method" value="DELETE" type="hidden">
                            <input type="hidden" name="_token" :value="csrf">
                            <div class="btn-group btn-group-sm pull-right list-table" role="group">
                                <a :href="showUrl + '/' + data.id" class="btn btn-sm btn-info" title="Show Service">
                                    <i class="fa fa-calendar-alt"></i>
                                </a>
                                <a :href="editUrlComposition(data.id)" class="btn btn-sm btn-primary" title="Edit Service">
                                    <i class="fa fa-pencil-alt"></i>
                                </a>
                                <button type="submit" :dusk="'delete-service-' + data.id" class="btn btn-sm btn-danger" title="Delete Service" onclick="return confirm(&quot;Delete Service?&quot;)">
                                    <i class="fa fa-trash-alt"></i>
                                </button>
                            </div>
                        </form>
                    </td>
                </tr>
            </tbody>
        </table>

        <nav v-if="pagination && tableData.length > 0">
            <ul class="pagination">
                <li class="page-item" :class="{'disabled' : currentPage === 1}">
                    <a class="page-link" href="#" @click.prevent="changePage(currentPage - 1)">Previous</a>
                </li>
                <li v-for="(page, key2) in pagesNumber" class="page-item"
                    :class="{'active': page == pagination.current_page}"
                    :key="key2"
                >
                <a href="javascript:void(0)" @click.prevent="changePage(page)" class="page-link">{{ page }}</a>
                </li>
                <li class="page-item" :class="{'disabled': currentPage === pagination.last_page }">
                <a class="page-link" href="#" @click.prevent="changePage(currentPage + 1)">Next</a>
                </li>
                <span style="margin-top: 8px;"> &nbsp; <i>Displaying {{ pagination.data.length }} of {{ pagination.total }} entries.</i></span>
            </ul>
        </nav>
    </div>
</template>

<script type="text/ecmascript-6">
export default {
    props: {
        fetchUrl: { type: String, required: true },
        columns: { type: Array, required: true },
        showUrl: { type: String, required: true },
        editUrl: { type: String, required: true },
        deleteUrl: { type: String, required: true }
    },
    data() {
        return {
            tableData: [],
            url: '',
            pagination: {
                meta: { to: 1, from: 1 }
            },
            offset: 4,
            currentPage: 1,
            perPage: 20,
            sortedColumn: this.columns[0],
            search: '',
            order: 'asc',
            csrf: document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        }
    },
    watch: {
        fetchUrl: {
            handler: function(fetchUrl) {
                this.url = fetchUrl;
            },
            immediate: true
        },
        search: {
            handler: function(search) {
                this.search = search;
                this.fetchData();
            },
            immediate: true
        }
    },
    created() {
        return this.fetchData();
    },
    computed: {
        /**
         * Get the pages number array for displaying in the pagination.
         * */
        pagesNumber() {
            if (!this.pagination.to) {
                return [];
            }
            let from = this.pagination.current_page - this.offset;
            if (from < 1) {
                from = 1;
            }
            let to = from + (this.offset * 2);
            if (to >= this.pagination.last_page) {
                to = this.pagination.last_page;
            }
            let pagesArray = [];
            for (let page = from; page <= to; page++) {
                pagesArray.push(page);
            }
            return pagesArray;
        },
        /**
         * Get the total data displayed in the current page.
         * */
        totalData() {
            return (this.pagination.to - this.pagination.from) + 1;
        }
    },
    methods: {
        editUrlComposition(sv_id){
            return this.editUrl.replace('//edit','/' + sv_id + '/edit');
        },
        fetchData() {
            let dataFetchUrl = `${this.url}?page=${this.currentPage}&column=${this.sortedColumn}&order=${this.order}&per_page=${this.perPage}&search=${this.search}`;

            axios.get(dataFetchUrl)
                .then(({ data }) => {
                    this.pagination = data;
                    this.pagination.meta = data;
                    this.tableData = data.data;
                }).catch(error => this.tableData = []);
        },
        /**
         * Get the serial number.
         * @param key
         * */
        serialNumber(key) {
            return (this.currentPage - 1) * this.perPage + 1 + key;
        },
        /**
         * Change the page.
         * @param pageNumber
         */
        changePage(pageNumber) {
            this.currentPage = pageNumber;
            this.fetchData();
        },
        /**
         * Sort the data by column.
         * */
        sortByColumn(column) {
            if( column != 'service_provider') {
                if (column === this.sortedColumn) {
                    this.order = (this.order === 'asc') ? 'desc' : 'asc';
                } else {
                    this.sortedColumn = column;
                    this.order = 'asc';
                }
                this.fetchData();
            }
        }
    },
    filters: {
        columnHead(value) {
            return value.split('_').join(' ').toUpperCase();
        }
    },
    name: 'DataTable'
}
</script>
