/*----------------------------
1. Side Navigation Code
-----------------------------*/
clickHideShow({
    open: '#sideMenuOpen',
    close: '#sideMenuClose',
    contains: '#wrapper',
    box: '#side-menu-area',
    classContain: 'side-menu-opened',
    animateOut: 'menuOut',
    animateIn: 'showIn'
})

function clickHideShow(options) {
    var openButton = document.querySelector(options.open);
    var closeButton = document.querySelector(options.close);
    var containElement = document.querySelector(options.contains);
    var boxElement = document.querySelector(options.box);
    if (openButton) {
        openButton.onclick = function () {
            openButton.classList.add('active');
            containElement.classList.add(options.classContain);
            boxElement.classList.remove(options.animateOut, 'collapse');
            boxElement.classList.add(options.animateIn);
        }
    }
    if (closeButton) {
        closeButton.onclick = function () {
            openButton.classList.remove('active');
            containElement.classList.remove(options.classContain);
            boxElement.classList.remove(options.animateIn);
            boxElement.classList.add(options.animateOut);
        }
    }
}

/*----------------------------
2. Welcome Carousel Code
-----------------------------*/
Slider({
    slider: '#welcome-slider',
    items: 1
})

function Slider(options) {
    var slider = document.querySelector(options.slider);
    var Interval;
    if (slider) {
        var slideBox = slider.querySelector('.slide-inner');
        var itemElements = Array.from(slideBox.querySelectorAll('.slide-item'));
        var contentElements = [];
        var imageSRC = [];
        for (var i = 0; i < itemElements.length; i++) {
            contentElements[i] = itemElements[i].innerHTML;
            imageSRC[i] = itemElements[i].getAttribute('data-img');
        }
        var indexItem = 1;
        var indicatorItems = Array.from(slider.querySelectorAll('.indicator-item'));
        var maskImageElement = slideBox.querySelector('.image-mask');
        var showIndicators = Array.from(slider.querySelectorAll('.indicator-sub li'));


        var posFirst;
        var posLast;
        slider.onmousedown = function (e) {
            posFirst = parseInt(e.clientX);
        }

        slider.onmouseup = function (e) {
            posLast = parseInt(e.clientX);
            var activeIndi = indicatorItems.reduce(function (active, element) {
                if (element.classList.contains('active')) {
                    active = element;
                };
                return active;
            }, '')
            if (posFirst > posLast) {
                testcode = parseInt(activeIndi.getAttribute('data-id')) + 1;
                if (testcode == itemElements.length) {
                    testcode = 0;
                }
                playSlider(testcode)
            }
            if (posFirst < posLast) {
                var testcode = parseInt(activeIndi.getAttribute('data-id')) - 1;

                if (testcode < 0) {
                    testcode = itemElements.length - 1;
                }

                playPrev(testcode)
            }
        }

        window.onload = function () {
            clearInterval(Interval);
            Interval = setInterval(() => {
                playSlider()
            }, 5000);
        }

        slider.onmouseover = function () {
            clearInterval(Interval);
        }

        slider.onmouseout = function () {
            clearInterval(Interval);
            Interval = setInterval(() => {
                playSlider()
            }, 5000);
        }

        indicatorItems.forEach(function (indicator) {
            indicator.onclick = function () {
                var testcode = indicator.getAttribute('data-id');
                playSlider(testcode)
            }
        })
    }

    function playSlider(testcode) {
        var activeIndicator = indicatorItems.reduce(function (active, indicator) {
            if (indicator.classList.contains('active')) {
                active = indicator;
            }
            return active;
        })

        var activeSub = showIndicators.reduce(function (active, indicator) {
            if (indicator.classList.contains('active')) {
                active = indicator;
            }
            return active;
        })
        var indexCurrent;

        for (var i = 0; i < options.items; ++i) {
            if (testcode) {
                indexCurrent = testcode;
            } else {
                indexCurrent = indexItem + i;
                if (indexCurrent >= itemElements.length) {
                    indexCurrent = indexCurrent - itemElements.length;
                }
            }
            activeIndicator.classList.remove('active');
            indicatorItems[indexCurrent].classList.add('active');
            activeSub.classList.remove('active');
            showIndicators[indexCurrent].classList.add('active');

            var indexOut = indexCurrent - 1;
            if (indexOut < 0) {
                indexOut = itemElements.length - 1;
            }

            var promise = new Promise(function (resolve) {
                resolve(function () {
                    itemElements[0].innerHTML = contentElements[indexOut];
                })

                maskImageElement.querySelector('img').src = imageSRC[indexOut];
                slideOut(maskImageElement, 0, 'maskIn', 'maskOut', 'mask-down', 'mask-up');
            })

            promise
                .then(function () {
                    setTimeout(() => {
                        itemElements[0].innerHTML = contentElements[indexCurrent];
                        maskImageElement.querySelector('img').src = imageSRC[indexCurrent];
                        slideIn(maskImageElement, 0, 'maskIn', 'maskOut', 'mask-down');
                    }, 700);
                })
        }
        indexItem++;
        if (indexItem == itemElements.length) {
            indexItem = 0;
        }
    }

    function playPrev(testcode) {
        var activeIndicator = indicatorItems.reduce(function (active, indicator) {
            if (indicator.classList.contains('active')) {
                active = indicator;
            }
            return active;
        })

        var activeSub = showIndicators.reduce(function (active, indicator) {
            if (indicator.classList.contains('active')) {
                active = indicator;
            }
            return active;
        })
        var indexCurrent;

        for (var i = 0; i < options.items; ++i) {
            if (testcode) {
                indexCurrent = testcode;
            } else {
                indexCurrent = indexItem - i;
                if (indexCurrent < 0) {
                    indexCurrent = indexCurrent + itemElements.length;
                }
            }
            activeIndicator.classList.remove('active');
            indicatorItems[indexCurrent].classList.add('active');
            activeSub.classList.remove('active');
            showIndicators[indexCurrent].classList.add('active');

            var indexIn = indexCurrent + 1;
            if (indexIn == itemElements.length) {
                indexIn = 0;
            }

            var promise = new Promise(function (resolve) {
                resolve(function () {
                    itemElements[0].innerHTML = contentElements[indexIn];
                })

                maskImageElement.querySelector('img').src = imageSRC[indexIn];
                slideOut(maskImageElement, 0, 'fadeOut', 'hideOut', 'mask-down', 'mask-up');
            })

            promise
                .then(function () {
                    setTimeout(() => {
                        itemElements[0].innerHTML = contentElements[indexCurrent];
                        maskImageElement.querySelector('img').src = imageSRC[indexCurrent];
                        slideIn(maskImageElement, 0, 'fadeOut', 'hideOut', 'mask-down');
                    }, 700);
                })
        }
        indexItem--;
        if (indexItem < 0) {
            indexItem = itemElements.length - 1;
        }
    }

    function slideIn(image, index, maskIn, maskOut, maskDown) {
        var captionElement = itemElements[index].querySelector('.content-caption');
        var contentWrapper = itemElements[index].querySelector('.content-wrapper');
        var contentBackground = itemElements[index].querySelector('.content-background');
        var fakeBackground = itemElements[index].querySelector('.fake-background');
        var imageCavas = itemElements[index].querySelector('.image-cavas');
        image.style.animationDuration = '1s';
        image.classList.remove(maskOut);
        image.classList.add(maskIn);
        contentBackground.classList.add(maskDown);
        contentWrapper.style.animationDelay = '1s';
        contentWrapper.classList.add(maskDown);
        fakeBackground.classList.add(maskIn);
        captionElement.style.animationDelay = '700ms';
        captionElement.classList.add(maskDown);
        imageCavas.style.animationDelay = '700ms';
        imageCavas.classList.add(maskIn);
    }

    function slideOut(image, index, maskIn, maskOut, maskDown, maskUp) {
        var captionElement = itemElements[index].querySelector('.content-caption');
        var contentWrapper = itemElements[index].querySelector('.content-wrapper');
        var contentBackground = itemElements[index].querySelector('.content-background');
        var fakeBackground = itemElements[index].querySelector('.fake-background');
        var imageCavas = itemElements[index].querySelector('.image-cavas');
        imageCavas.style.animationDelay = '0ms';
        imageCavas.classList.remove(maskIn);
        imageCavas.classList.add(maskOut);
        image.style.animationDuration = '2s';
        image.classList.remove(maskIn);
        image.classList.add(maskOut);
        contentBackground.classList.remove(maskDown);
        contentBackground.classList.add(maskUp);
        contentWrapper.style.animationDelay = '500ms';
        contentWrapper.classList.remove(maskDown);
        contentWrapper.classList.add(maskUp);
        captionElement.style.animationDelay = '500ms';
        captionElement.classList.remove(maskDown);
        captionElement.classList.add(maskUp);
        fakeBackground.style.animationDuration = '2s';
        fakeBackground.classList.remove(maskIn);
        fakeBackground.classList.add(maskOut);
    }
}

/*----------------------------
3. Carousel Code
-----------------------------*/
Carousel({
    box: '#testimonials',
    itemSelector: '.slide-item',
    items: 1
})

Carousel({
    box: '#product-details',
    itemSelector: '.slide-item',
    items: 1
})
Carousel({
    box: '#relatedProduct',
    itemSelector: '.slide-item',
    items: 3
})

Carousel({
    box: '#carouselBrands',
    itemSelector: '.slide-item',
    items: 5
})

function Carousel(options) {
    var carouselElement = document.querySelector(options.box);
    var indexItem = 2;
    var Interval;
    if (carouselElement) {
        var itemElements = carouselElement.querySelectorAll(options.itemSelector);
        var contentElements = Array.from(itemElements).reduce(function (contents, item, index) {
            contents[index] = item.innerHTML;
            return contents;
        }, []);
        if (options.box == '#relatedProduct') {
            if (screen.width > 768) {
                if (screen.width < 992) {
                    options.items = 2;
                } else {
                    options.item = 3;
                }
            } else {
                options.items = 1;
            }
        }
        var indicatorElements = carouselElement.querySelectorAll('.slide-indicators .indicator-item');

        Array.from(itemElements).forEach(function (element) {
            var posFirst;
            var posLast;
            element.style.flex = '0 0 ' + 100 / options.items + '%';

            element.onmousedown = function (e) {
                posFirst = parseInt(e.clientX);
            }

            element.onmouseup = function (e) {
                posLast = parseInt(e.clientX);
                var activeIndi = Array.from(indicatorElements).reduce(function (active, element) {
                    if (element.classList.contains('active')) {
                        active = element;
                    };
                    return active;
                }, '')
                if (posFirst > posLast) {
                    var testcode = parseInt(activeIndi.getAttribute('data-id')) + 1;
                    if (testcode == itemElements.length) {
                        testcode = 0;
                    }
                    playSlider(testcode)
                }
                if (posFirst < posLast) {
                    var testcode = parseInt(activeIndi.getAttribute('data-id')) - 1;

                    if (testcode && testcode < 0) {
                        testcode = itemElements.length - 1;
                    }
                    playPrev(testcode)
                }
            }
        })
        if (itemElements.length >= options.items) {
            Interval = setInterval(() => {
                playSlider()
            }, 5000);

            window.onload = function () {
                clearInterval(Interval);
                Interval = setInterval(() => {
                    playSlider()
                }, 5000);
            }

            carouselElement.onmouseover = function () {
                clearInterval(Interval);
            }

            carouselElement.onmouseout = function () {
                clearInterval(Interval);
                Interval = setInterval(() => {
                    playSlider()
                }, 5000);
            }
        }
        Array.from(indicatorElements).forEach(function (indicator) {
            indicator.onclick = function () {
                var testcode = parseInt(indicator.getAttribute('data-id'));
                playSlider(testcode)
            }
        })
    }

    function playSlider(testcode) {
        if (indicatorElements.length != 0) {
            var activeIndicator = Array.from(indicatorElements).reduce(function (active, indicator) {
                if (indicator.classList.contains('active')) {
                    active = indicator;
                }
                return active;
            })
        }

        var indexCurrent;

        for (var i = 0; i < options.items; ++i) {
            if (testcode) {
                indexCurrent = testcode;
            } else {
                indexCurrent = indexItem + i;
                if (indexCurrent >= itemElements.length) {
                    indexCurrent = indexCurrent - itemElements.length;
                }
            }
            if (indicatorElements.length != 0) {
                if (indicatorElements.length != 0) {
                    activeIndicator.classList.remove('active');
                    indicatorElements[indexCurrent].classList.add('active');
                }
            }

            var indexOut = indexCurrent - 1;
            if (indexOut < 0) {
                indexOut = itemElements.length - 1;
            }
            var promise = new Promise(function (resolve) {
                resolve(function () {
                    itemElements[i].innerHTML = contentElements[indexOut];
                })

                slideOut(itemElements, i, 'maskIn', 'maskOut');
            })

            promise
                .then(function () {
                    setTimeout(() => {
                        for (i = 0; i < options.items; i++) {
                            var tmpIndex = indexCurrent + i;
                            if (tmpIndex >= itemElements.length) {
                                tmpIndex = tmpIndex - itemElements.length;
                            }
                            itemElements[i].innerHTML = contentElements[tmpIndex];
                            slideIn(itemElements, i, 'maskIn', 'maskOut');
                        }
                    }, 700);
                })
        }
        indexItem++;
        if (indexItem == itemElements.length) {
            indexItem = 0;
        }
    }

    function playPrev(testcode) {
        if (indicatorElements.length != 0) {
            var activeIndicator = Array.from(indicatorElements).reduce(function (active, indicator) {
                if (indicator.classList.contains('active')) {
                    active = indicator;
                }
                return active;
            })
        }
        var indexCurrent;

        for (var i = 0; i < options.items; ++i) {
            if (testcode) {
                indexCurrent = testcode;
            } else {
                indexCurrent = indexItem - i;
                if (indexCurrent < 0) {
                    indexCurrent = indexCurrent + itemElements.length;
                }
            }

            if (indicatorElements.length != 0) {
                activeIndicator.classList.remove('active');
                indicatorElements[indexCurrent].classList.add('active');
            }
            var indexIn = indexCurrent + 1;
            if (indexIn == itemElements.length) {
                indexIn = 0;
            }

            var promise = new Promise(function (resolve) {
                resolve(function () {
                    itemElements[0].innerHTML = contentElements[indexIn];
                })
                itemElements[0].classList.remove('maskIn')

                slideOut(itemElements, 0, 'fadeOut', 'hideOut');
            })

            promise
                .then(function () {
                    setTimeout(() => {
                        for (var i = 0; i < itemElements.length; i++) {
                            var tmpIndex = indexCurrent - i;
                            if (tmpIndex < 0) {
                                tmpIndex = tmpIndex + itemElements.length;
                            }
                            itemElements[i].innerHTML = contentElements[tmpIndex];
                            slideIn(itemElements, i, 'fadeOut', 'hideOut');
                        }
                    }, 700);
                })
        }
        indexItem--;
        if (indexItem < 0) {
            indexItem = itemElements.length - 1;
        }
    }

    function slideOut(item, index, animateIn, animateOut) {
        item[index].classList.remove(animateIn)
        item[index].classList.add(animateOut)
    }

    function slideIn(item, index, animateIn, animateOut) {
        item[index].classList.add(animateIn)
        item[index].classList.remove(animateOut)
    }
}

/*------------------------------
4. Scroll Show Product Item Code
------------------------------*/
ScrollDisplay({
    box: ['.shop-new-arrivals', '.offer-area', '.shop-pagination'],
    itemSelector: ['.prd-item', '.offer-content', '.page-link']
})

function ScrollDisplay(options) {
    var boxElements = document.querySelectorAll(options.box);
    Array.from(boxElements).forEach(function (box) {
        var count = 0;
        options.itemSelector.forEach(function (selector) {
            var itemElements = box.querySelectorAll(selector);
            Array.from(itemElements).forEach(function (element) {
                var posY = element.getBoundingClientRect().top;
                if (screen.height > posY) {
                    var timeDelay = 0.2;
                    if (screen.height >= posY) {
                        element.classList.add('scrollDown');
                        element.style.animationDelay = timeDelay * count + 's';
                    }
                    count++;
                }
            })
        })
    })

    window.onscroll = function () {
        Array.from(boxElements).forEach(function (box) {
            var count = 0;
            options.itemSelector.forEach(function (selector) {
                var itemElements = box.querySelectorAll(selector);
                Array.from(itemElements).forEach(function (element) {
                    var posY = element.getBoundingClientRect().top;
                    if (window.pageYOffset >= posY) {
                        var timeDelay = 0.2;
                        if (screen.height >= posY) {
                            element.classList.add('scrollDown');
                            element.style.animationDelay = timeDelay * count + 's';
                        }
                        count++;
                    }
                })
            })
        })
    }

}

/*------------------------------
5. Back Home Code
------------------------------*/
BackHome({
    idSelector: '#home-back'
})

function BackHome(options) {
    let butEl = getParent(document.querySelector(options.idSelector), '.home-back');
    if (butEl) {
        setInterval(() => {
            if (window.pageYOffset > 0) {
                butEl.style.opacity = 1;
            } else {
                butEl.style.opacity = 0;
            }
        }, 500);

        butEl.onclick = function (e) {
            e.preventDefault();
            window.scrollTo({
                top: 0,
                behavior: "smooth"
            })
        }
    }
}

/*------------------------------
5. Back Home Code
------------------------------*/
function ModalData() {
    var tmpTestEls = document.querySelectorAll('[data-toggle=savetmp]');
    if (tmpTestEls) {
        Array.from(tmpTestEls).forEach(function (tmp) {
            let inforText = tmp.innerText.split('1. Information:')[1].split('2.')[0].trim();
            var buttonModal = getParent(tmp, '.prd-item').querySelector('a[data-toggle=modal]');
            if (buttonModal) {
                buttonModal.setAttribute('data-detail', inforText);
            }
        })
    }
}
ModalData()

/*------------------------------
6. Reply Comments Code
------------------------------*/
function OpenComments() {
    var opParEls = document.querySelectorAll('[data-toggle=comm-parColl]');
    if (opParEls) {
        Array.from(opParEls).forEach(function (oppa) {
            oppa.onclick = function (e) {
                e.preventDefault();
                var tarEl = document.querySelector(oppa.getAttribute('data-target'));
                if (tarEl) {
                    if (tarEl.classList.contains('collapse')) {
                        tarEl.classList.remove('collapse');
                    }
                }
                let writTrEls = document.querySelector(oppa.getAttribute('data-tarblock')).querySelectorAll('[data-toggle=writeComm]');
                if (writTrEls) {
                    Array.from(writTrEls).forEach(function (wr) {
                        if (!wr.classList.contains('collapse')) {
                            wr.classList.add('collapse')
                        }
                    })
                }
            }
        })
    }
    var opEls = document.querySelectorAll('[data-toggle=comm-collapse]');
    if (opEls) {
        Array.from(opEls).forEach(function (op) {
            op.onclick = function (e) {
                e.preventDefault();
                getParent(op, '.comm-item-group').querySelector(op.getAttribute('data-target')).classList.remove('collapse');
                sessionStorage['saveOpenCommend'] = op.getAttribute('data-target');
            }
            if (sessionStorage['saveOpenCommend'] !== '') {
                var elac = document.querySelector(sessionStorage['saveOpenCommend']);
                if (elac) {
                    if (elac.classList.contains('collapse')) {
                        elac.classList.remove('collapse');
                    }
                }
            }
        })
    }
}

OpenComments()

function ReplyComm() {
    var repEls = document.querySelectorAll('[data-toggle=reply]');
    if (repEls) {
        Array.from(repEls).forEach(function (rep) {
            rep.onclick = function (e) {
                e.preventDefault();
                var writeEls = getParent(rep, '.comm-area').querySelectorAll('[data-toggle=writeComm]');
                var saveEl = getParent(rep, rep.getAttribute('data-parent')).querySelector('.comm-child');
                if (sessionStorage['saveOpenCommend']) {
                    document.querySelector(sessionStorage['saveOpenCommend']).classList.add('collapse');
                }
                saveEl.classList.remove('collapse');
                sessionStorage['saveOpenCommend'] = '#' + saveEl.id;
                if (writeEls) {
                    Array.from(writeEls).forEach(function (writ) {
                        if (getParent(rep, rep.getAttribute('data-parent')).contains(writ)) {
                            writ.classList.remove('collapse');
                            var dataId = rep.getAttribute('data-id');
                            var textBoxEl = writ.querySelector('#textareaEl');
                            if (textBoxEl) {
                                var textareaEls = textBoxEl.children;
                                if (textareaEls) {
                                    Array.from(textareaEls).forEach(function (ta) {
                                        textBoxEl.removeChild(ta);
                                    })
                                    console.log(textareaEls)
                                    var Idpromise = new Promise(function (resolve) {
                                        var textCreatEl = document.createElement('textarea');
                                        textCreatEl.name = 'comm_details';
                                        textCreatEl.classList.add('w-100')
                                        var dataDeta = rep.getAttribute('data-detail');
                                        if (dataDeta) {
                                            dataContent = dataDeta;
                                        } else {
                                            dataContent = '';
                                        }
                                        textCreatEl.value = dataContent;
                                        textCreatEl.id = 'comm_detail_' + rep.getAttribute('data-setID');
                                        textBoxEl.appendChild(textCreatEl);
                                        resolve(dataContent + '%' + textCreatEl.id);
                                    })
                                    Idpromise
                                        .then(function (data) {
                                            CKEDITOR.replace(data.split('%')[1]);
                                        })
                                }

                                if (dataId) {
                                    writ.querySelector('input[name=comm_repfor]').value = dataId;
                                }
                                var dataTos = rep.getAttribute('data-togsub');
                                if (dataTos === 'edit') {
                                    writ.querySelector('button[type=submit]').name = 'editCommpr';
                                }
                            } else {
                                if (!writ.classList.contains('collapse')) {
                                    writ.classList.add('collapse');
                                }
                            }
                        }
                    })
                    var testWrit = Array.from(writeEls).map(function (val, ind) {
                        if (!val.classList.contains('collapse')) {
                            return false;
                        }
                    })
                }
            }
        })
    }
}
ReplyComm()

/*------------------------------
6. Show Ratting Code
------------------------------*/
function ShowRatt() {
    var ratEls = document.querySelectorAll('[data-toggle=showratt]');
    if (ratEls) {
        Array.from(ratEls).forEach(function (rat) {
            var widthStars = parseFloat(parseFloat(rat.getAttribute('data-ratt')) / 5) * 100;
            document.styleSheets[0].deleteRule('.stars:after {width: 100%}', 0);
            document.styleSheets[0].insertRule('.stars:after {width:' + widthStars + '%}', 0);
        })
    }
}
ShowRatt()

/*------------------------------
7. Slider Range Price Active Code
------------------------------*/
function getVals() {
    var parent = document.querySelector('#filter-price');
    var slides = parent.querySelectorAll('input[type=range]');
    var widthFirst = parseFloat(parent.clientWidth);
    var baseUnit = widthFirst / parseFloat(slides[1].max);

    if (sessionStorage['rangeFilter']) {
        valueMax = sessionStorage['rangeFilter'].split('%')[1];
        valueMin = sessionStorage['rangeFilter'].split('%')[0];
    } else {
        var valueMin = parseFloat(slides[0].value);
        var valueMax = parseFloat(slides[1].value);
        if (valueMin > valueMax) {
            var tmpVal = valueMax;
            valueMax = valueMin;
            valueMin = tmpVal;
        }
    }
    var handleBox = parent.querySelector('.range-slider');
    if (valueMax >= parseFloat(slides[1].max) / 3 && valueMax < parseFloat(slides[1].max) * 2 / 3) {
        handleBox.style.width = ((valueMax - valueMin) * baseUnit - 7.5) + 'px';
    } else {
        if (valueMax >= parseFloat(slides[1].max) * 2 / 3) {
            handleBox.style.width = ((valueMax - valueMin) * baseUnit - 15) + 'px';
        } else {
            handleBox.style.width = ((valueMax - valueMin) * baseUnit) + 'px';

        }
    }
    handleBox.style.left = valueMin * baseUnit + 'px';
    parent.querySelector('.price-min').innerHTML = valueMin;
    parent.querySelector('.price-max').innerHTML = valueMax;
    slides[0].value = valueMin;
    slides[1].value = valueMax;
    sessionStorage['rangeFilter'] = [valueMin, valueMax].join('%');
    parent.querySelector('input[name=price_filter]').value = [valueMin, valueMax].join('%');
}

function RangeFilter() {
    var slideSections = document.querySelector('#filter-price');
    if (slideSections) {
        var sliders = slideSections.querySelectorAll('input[type="range"]');
        Array.from(sliders).forEach(function (slide) {
            slide.oninput = function () {
                sessionStorage.removeItem('rangeFilter');
                getVals();
            }
            slide.onmouseup = function (e) {
                slideSections.querySelector('form').submit()
            }
        })
        if (sessionStorage['rangeFilter']) {
            getVals()
        }
    }
}
RangeFilter()

/*------------------------------
8. MD5 Convert Pasword Code
------------------------------*/
function MD5(string) {

    function RotateLeft(lValue, iShiftBits) {
        return (lValue << iShiftBits) | (lValue >>> (32 - iShiftBits));
    }

    function AddUnsigned(lX, lY) {
        var lX4, lY4, lX8, lY8, lResult;
        lX8 = (lX & 0x80000000);
        lY8 = (lY & 0x80000000);
        lX4 = (lX & 0x40000000);
        lY4 = (lY & 0x40000000);
        lResult = (lX & 0x3FFFFFFF) + (lY & 0x3FFFFFFF);
        if (lX4 & lY4) {
            return (lResult ^ 0x80000000 ^ lX8 ^ lY8);
        }
        if (lX4 | lY4) {
            if (lResult & 0x40000000) {
                return (lResult ^ 0xC0000000 ^ lX8 ^ lY8);
            } else {
                return (lResult ^ 0x40000000 ^ lX8 ^ lY8);
            }
        } else {
            return (lResult ^ lX8 ^ lY8);
        }
    }

    function F(x, y, z) {
        return (x & y) | ((~x) & z);
    }
    function G(x, y, z) {
        return (x & z) | (y & (~z));
    }
    function H(x, y, z) {
        return (x ^ y ^ z);
    }
    function I(x, y, z) {
        return (y ^ (x | (~z)));
    }

    function FF(a, b, c, d, x, s, ac) {
        a = AddUnsigned(a, AddUnsigned(AddUnsigned(F(b, c, d), x), ac));
        return AddUnsigned(RotateLeft(a, s), b);
    };

    function GG(a, b, c, d, x, s, ac) {
        a = AddUnsigned(a, AddUnsigned(AddUnsigned(G(b, c, d), x), ac));
        return AddUnsigned(RotateLeft(a, s), b);
    };

    function HH(a, b, c, d, x, s, ac) {
        a = AddUnsigned(a, AddUnsigned(AddUnsigned(H(b, c, d), x), ac));
        return AddUnsigned(RotateLeft(a, s), b);
    };

    function II(a, b, c, d, x, s, ac) {
        a = AddUnsigned(a, AddUnsigned(AddUnsigned(I(b, c, d), x), ac));
        return AddUnsigned(RotateLeft(a, s), b);
    };

    function ConvertToWordArray(string) {
        var lWordCount;
        var lMessageLength = string.length;
        var lNumberOfWords_temp1 = lMessageLength + 8;
        var lNumberOfWords_temp2 = (lNumberOfWords_temp1 - (lNumberOfWords_temp1 % 64)) / 64;
        var lNumberOfWords = (lNumberOfWords_temp2 + 1) * 16;
        var lWordArray = Array(lNumberOfWords - 1);
        var lBytePosition = 0;
        var lByteCount = 0;
        while (lByteCount < lMessageLength) {
            lWordCount = (lByteCount - (lByteCount % 4)) / 4;
            lBytePosition = (lByteCount % 4) * 8;
            lWordArray[lWordCount] = (lWordArray[lWordCount] | (string.charCodeAt(lByteCount) << lBytePosition));
            lByteCount++;
        }
        lWordCount = (lByteCount - (lByteCount % 4)) / 4;
        lBytePosition = (lByteCount % 4) * 8;
        lWordArray[lWordCount] = lWordArray[lWordCount] | (0x80 << lBytePosition);
        lWordArray[lNumberOfWords - 2] = lMessageLength << 3;
        lWordArray[lNumberOfWords - 1] = lMessageLength >>> 29;
        return lWordArray;
    };

    function WordToHex(lValue) {
        var WordToHexValue = "", WordToHexValue_temp = "", lByte, lCount;
        for (lCount = 0; lCount <= 3; lCount++) {
            lByte = (lValue >>> (lCount * 8)) & 255;
            WordToHexValue_temp = "0" + lByte.toString(16);
            WordToHexValue = WordToHexValue + WordToHexValue_temp.substr(WordToHexValue_temp.length - 2, 2);
        }
        return WordToHexValue;
    };

    function Utf8Encode(string) {
        string = string.replace(/\r\n/g, "\n");
        var utftext = "";

        for (var n = 0; n < string.length; n++) {

            var c = string.charCodeAt(n);

            if (c < 128) {
                utftext += String.fromCharCode(c);
            }
            else if ((c > 127) && (c < 2048)) {
                utftext += String.fromCharCode((c >> 6) | 192);
                utftext += String.fromCharCode((c & 63) | 128);
            }
            else {
                utftext += String.fromCharCode((c >> 12) | 224);
                utftext += String.fromCharCode(((c >> 6) & 63) | 128);
                utftext += String.fromCharCode((c & 63) | 128);
            }

        }

        return utftext;
    };

    var x = Array();
    var k, AA, BB, CC, DD, a, b, c, d;
    var S11 = 7, S12 = 12, S13 = 17, S14 = 22;
    var S21 = 5, S22 = 9, S23 = 14, S24 = 20;
    var S31 = 4, S32 = 11, S33 = 16, S34 = 23;
    var S41 = 6, S42 = 10, S43 = 15, S44 = 21;

    string = Utf8Encode(string);

    x = ConvertToWordArray(string);

    a = 0x67452301;
    b = 0xEFCDAB89;
    c = 0x98BADCFE;
    d = 0x10325476;

    for (k = 0; k < x.length; k += 16) {
        AA = a;
        BB = b;
        CC = c;
        DD = d;
        a = FF(a, b, c, d, x[k + 0], S11, 0xD76AA478);
        d = FF(d, a, b, c, x[k + 1], S12, 0xE8C7B756);
        c = FF(c, d, a, b, x[k + 2], S13, 0x242070DB);
        b = FF(b, c, d, a, x[k + 3], S14, 0xC1BDCEEE);
        a = FF(a, b, c, d, x[k + 4], S11, 0xF57C0FAF);
        d = FF(d, a, b, c, x[k + 5], S12, 0x4787C62A);
        c = FF(c, d, a, b, x[k + 6], S13, 0xA8304613);
        b = FF(b, c, d, a, x[k + 7], S14, 0xFD469501);
        a = FF(a, b, c, d, x[k + 8], S11, 0x698098D8);
        d = FF(d, a, b, c, x[k + 9], S12, 0x8B44F7AF);
        c = FF(c, d, a, b, x[k + 10], S13, 0xFFFF5BB1);
        b = FF(b, c, d, a, x[k + 11], S14, 0x895CD7BE);
        a = FF(a, b, c, d, x[k + 12], S11, 0x6B901122);
        d = FF(d, a, b, c, x[k + 13], S12, 0xFD987193);
        c = FF(c, d, a, b, x[k + 14], S13, 0xA679438E);
        b = FF(b, c, d, a, x[k + 15], S14, 0x49B40821);
        a = GG(a, b, c, d, x[k + 1], S21, 0xF61E2562);
        d = GG(d, a, b, c, x[k + 6], S22, 0xC040B340);
        c = GG(c, d, a, b, x[k + 11], S23, 0x265E5A51);
        b = GG(b, c, d, a, x[k + 0], S24, 0xE9B6C7AA);
        a = GG(a, b, c, d, x[k + 5], S21, 0xD62F105D);
        d = GG(d, a, b, c, x[k + 10], S22, 0x2441453);
        c = GG(c, d, a, b, x[k + 15], S23, 0xD8A1E681);
        b = GG(b, c, d, a, x[k + 4], S24, 0xE7D3FBC8);
        a = GG(a, b, c, d, x[k + 9], S21, 0x21E1CDE6);
        d = GG(d, a, b, c, x[k + 14], S22, 0xC33707D6);
        c = GG(c, d, a, b, x[k + 3], S23, 0xF4D50D87);
        b = GG(b, c, d, a, x[k + 8], S24, 0x455A14ED);
        a = GG(a, b, c, d, x[k + 13], S21, 0xA9E3E905);
        d = GG(d, a, b, c, x[k + 2], S22, 0xFCEFA3F8);
        c = GG(c, d, a, b, x[k + 7], S23, 0x676F02D9);
        b = GG(b, c, d, a, x[k + 12], S24, 0x8D2A4C8A);
        a = HH(a, b, c, d, x[k + 5], S31, 0xFFFA3942);
        d = HH(d, a, b, c, x[k + 8], S32, 0x8771F681);
        c = HH(c, d, a, b, x[k + 11], S33, 0x6D9D6122);
        b = HH(b, c, d, a, x[k + 14], S34, 0xFDE5380C);
        a = HH(a, b, c, d, x[k + 1], S31, 0xA4BEEA44);
        d = HH(d, a, b, c, x[k + 4], S32, 0x4BDECFA9);
        c = HH(c, d, a, b, x[k + 7], S33, 0xF6BB4B60);
        b = HH(b, c, d, a, x[k + 10], S34, 0xBEBFBC70);
        a = HH(a, b, c, d, x[k + 13], S31, 0x289B7EC6);
        d = HH(d, a, b, c, x[k + 0], S32, 0xEAA127FA);
        c = HH(c, d, a, b, x[k + 3], S33, 0xD4EF3085);
        b = HH(b, c, d, a, x[k + 6], S34, 0x4881D05);
        a = HH(a, b, c, d, x[k + 9], S31, 0xD9D4D039);
        d = HH(d, a, b, c, x[k + 12], S32, 0xE6DB99E5);
        c = HH(c, d, a, b, x[k + 15], S33, 0x1FA27CF8);
        b = HH(b, c, d, a, x[k + 2], S34, 0xC4AC5665);
        a = II(a, b, c, d, x[k + 0], S41, 0xF4292244);
        d = II(d, a, b, c, x[k + 7], S42, 0x432AFF97);
        c = II(c, d, a, b, x[k + 14], S43, 0xAB9423A7);
        b = II(b, c, d, a, x[k + 5], S44, 0xFC93A039);
        a = II(a, b, c, d, x[k + 12], S41, 0x655B59C3);
        d = II(d, a, b, c, x[k + 3], S42, 0x8F0CCC92);
        c = II(c, d, a, b, x[k + 10], S43, 0xFFEFF47D);
        b = II(b, c, d, a, x[k + 1], S44, 0x85845DD1);
        a = II(a, b, c, d, x[k + 8], S41, 0x6FA87E4F);
        d = II(d, a, b, c, x[k + 15], S42, 0xFE2CE6E0);
        c = II(c, d, a, b, x[k + 6], S43, 0xA3014314);
        b = II(b, c, d, a, x[k + 13], S44, 0x4E0811A1);
        a = II(a, b, c, d, x[k + 4], S41, 0xF7537E82);
        d = II(d, a, b, c, x[k + 11], S42, 0xBD3AF235);
        c = II(c, d, a, b, x[k + 2], S43, 0x2AD7D2BB);
        b = II(b, c, d, a, x[k + 9], S44, 0xEB86D391);
        a = AddUnsigned(a, AA);
        b = AddUnsigned(b, BB);
        c = AddUnsigned(c, CC);
        d = AddUnsigned(d, DD);
    }

    var temp = WordToHex(a) + WordToHex(b) + WordToHex(c) + WordToHex(d);

    return temp.toLowerCase();
}

/*------------------------------
9. Show/Hide Message Box Code
------------------------------*/
function MessageBox() {
    var boxMess = document.getElementById('messenger-box');
    if (boxMess) {
        var notifyEl = boxMess.querySelector('.notify-mess');
        if (notifyEl) {
            notifyEl.style.bottom = 'calc(100% - ' + (getStyle(notifyEl, 'width') / 2 + getStyle(notifyEl, 'height') / 2 + 50) + 'px)';
            notifyEl.style.right = 'calc(100% - ' + (getStyle(notifyEl, 'width') / 2 - getStyle(notifyEl, 'height') / 2) + 'px)';
        }

        var boxBut = boxMess.querySelector('#block-button');
        if (boxBut) {
            boxBut.onclick = function () {
                var iconEl = boxBut.querySelector('i');
                if (iconEl.classList.contains('fa-angle-left')) {
                    boxMess.style.left = 'calc(100% - 25vw)';
                    iconEl.classList.remove('fa-angle-left');
                    iconEl.classList.add('fa-angle-right');
                } else {
                    boxMess.style.left = '100%';
                    iconEl.classList.add('fa-angle-left');
                    iconEl.classList.remove('fa-angle-right');
                }
            }
        }
        var inforEls = boxMess.querySelectorAll('.inbox-item .item-infor');
        if (inforEls) {
            Array.from(inforEls).forEach(function (elem) {
                elem.onclick = function () {
                    var timeEl = elem.querySelector('span');
                    if (timeEl) {
                        if (timeEl.classList.contains('collapse')) {
                            timeEl.classList.remove('collapse')
                        } else {
                            timeEl.classList.add('collapse')
                        }
                    }
                }
            })
        }
    }

}
MessageBox()