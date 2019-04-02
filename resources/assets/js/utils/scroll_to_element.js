let delay_mseconds = 500;

/**
 *
 * @param {String} context Class or Id of elements that the page will scroll to in the format '.class' or '#id' it supports all the querySelectorAll combinations
 * @param {Int} delay_mseconds The time that scrollIntoView will take to get into the element, by default is 500
 */
export default function scroll_to_element(context, delay_mseconds = 500) {
    delay_mseconds = delay_mseconds;
    const items_to_focus = document.querySelectorAll(context);
    Array.from(items_to_focus).forEach(item => item.addEventListener('click', add_scroll_to_element));
}
/**
 * Scrolls to element selected by the user in the DOM and that belong to the context
 */
function add_scroll_to_element() {
    let item = this;
    setTimeout(() => {
        item.scrollIntoView();
    }, delay_mseconds);
};