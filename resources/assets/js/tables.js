require('./bootstrap')

import DataTable from './components/DataTable'

const dTable = new Vue({
    el: '#dTables',
    components: {
        DataTable
    }
})