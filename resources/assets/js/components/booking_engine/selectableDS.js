import DragSelect from 'dragselect';
export default class SelectableDS {
    /**
     * Initialize Object Selectable
     */
    constructor(ds_class) {
        this.selectable = new DragSelect({
            selectables: document.querySelectorAll(ds_class),
            multiSelectMode: true
        });
    }

    /**
     * @param {String} CSS class including '.' where the functionality will be applied
     * @param {Array} Array of ids that are pre-selected
     */
    setInitialSelections(context, elements) {
        let selection = '';

        if (typeof elements !== 'undefined' && elements.length > 0) {
            for (let i = 0; i < elements.length; i++) {
                selection = elements[i];
                this.selectable.addSelection(document.querySelector(context + '#' + selection));
            }
        }
    }

    /**
     * Remove all the selected values
     */
    clear() {
        if (typeof this.selectable.clearSelection === "function") {
            this.selectable.clearSelection();
        }
    }

    /**
     * Return an array of all the selected elements by id
     */
    getSelection() {
        return this.selectable.getSelection();
    }

    /**
     * Return an array of all the selected elements by id
     */
    getSelectedValues() {
        return this.selectable.getSelection().map(item => item.id);
    }

    /**
     * Return an array of all the selected elements by context due to a bug with the current getSelection method
     */
    getSelectedValuesByContext(context) {
        const arr = document.querySelectorAll(context);
        let result = [];
        for (let i = 0; i < arr.length; i++) {
            result.push(arr[i].id);
        }
        return result;
    }

    /**
     * TODO - Submit Information
     */
    submit() {
        console.log('Selection:' + this.selectable.getSelection().map(item => item.id));
        console.log('Selection:' + this.selectable.getSelection().map(item => item.id));
    }
}