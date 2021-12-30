$(document).ready(function() {
    let tabBtnAttr = 'data-tab-btn';
    let tabBodyAttr = 'data-tab-body';
    let tabBtns = document.querySelectorAll(`[${tabBtnAttr}]`);
    let switchBtn = $('[data-switch]');
    let switchBody = $('[data-switcher]');

    for (let i = 0; i < tabBtns.length; i++) {
        tabBtns[i].addEventListener('click', tabClick);
    }

    function tabClick(event) {
        event.preventDefault();
        let currentTab = this;
        let currentTabName = currentTab.getAttribute(tabBtnAttr);
        let currentBody = document.querySelector(`[${tabBodyAttr}="${currentTabName}"]`);

        for (let e = 0; e < currentTab.parentElement.children.length; e++) {
            currentTab.parentElement.children[e].classList.remove('active');
        }
        for (let e = 0; e < currentBody.parentElement.children.length; e++) {
            currentBody.parentElement.children[e].classList.remove('active');
        }

        currentTab.classList.add('active');
        currentBody.classList.add('active');
    }

    $(switchBtn).click(function() {
        $(switchBody).toggleClass("active");
    })
});
