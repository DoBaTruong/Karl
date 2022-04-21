/*-----------------------------
:: 1.0 Link Href Prevent Defaulf
:: 2.0 Function Get Parent
:: 3.0 Get Style Element Code
:: 4.0 Check change size Element
:: 5.0 Collapse Code
:: 6.0 Show Sidebar Admin
:: 7.0 Canvas chart javascript code
:: 8.0 Tab Navigation JS Code
:: 9.0 Show Password JS Code
:: 10.0 Validator Form JS Code
:: 11.0 Modal Code
:: 12.0 Scroll Function Code
:: 13.0 Set Time Wait Code
:: 14.0 Check All JS Code
:: 15.0 Create Option Element JS Code
:: 16.0 Limit Line Text JS Code
:: 17.0 Select Color Multipart Product JS Code
:: 18.0 Show/Hide Message Box Code
-----------------------------*/



/*-----------------------------
1.0 Link Href Prevent Defaulf
-----------------------------*/
var linkElems = document.querySelectorAll('a[href="#"]');
Array.from(linkElems).forEach(function (link) {
    link.onclick = function (e) {
        e.preventDefault();
    }
})

/*-----------------------------
2.0 Function Get Parent
-----------------------------*/
function getParent(element, selector) {
    while (element.parentElement) {
        if (element.parentElement.matches(selector)) {
            return element.parentElement;
        }
        element = element.parentElement;
    }
}

/*-----------------------------
3.0 Get Style Element Code
-----------------------------*/
function getStyle(el, attr) {
    var attrVal = parseFloat(getComputedStyle(el).getPropertyValue(attr).split('px').shift());
    return attrVal;
}

/*-----------------------------
4.0 Check change size Element
-----------------------------*/
const boxCharts = document.querySelectorAll('.chart-item');
const chartObserver = new ResizeObserver(entries => {
    for (let entry of entries) {
        const width = parseFloat(entry.contentRect.width);
        const height = parseFloat(entry.contentRect.height);
        entry.target.querySelector('canvas').width = width - getStyle(entry.target, 'padding-left') * 2;
        entry.target.querySelector('canvas').height = height - getStyle(entry.target, 'padding-top') * 2;
        LineChart({
            canvas: '#monthly-chart',
            box: '.chart-item',
            month: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
            year: ['2020', '2021'],
            colorLine: ['#ff0000', '#0000ff'],
            values: getValChart()
        })

        PieChart({
            canvas: '#category-chart',
            parent: '.chart-item',
            cate: ['Clothes', 'Shoes', 'Eyewear', 'Accessories'],
            values: getValPie(),
            colors: ['#ee2222', '#13762f', '#1926bb', '#19bbb6']
        })
    }
})

Array.from(boxCharts).forEach(function (box) {
    chartObserver.observe(box);
})

window.onscroll = function () {
    LineChart({
        canvas: '#monthly-chart',
        box: '.chart-item',
        month: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
        year: ['2020', '2021'],
        colorLine: ['#ff0000', '#0000ff'],
        values: getValChart()
    })

    PieChart({
        canvas: '#category-chart',
        parent: '.chart-item',
        cate: ['Clothes', 'Shoes', 'Eyewear', 'Accessories'],
        values: getValPie(),
        colors: ['#ee2222', '#13762f', '#1926bb', '#19bbb6']
    })
}

/*-----------------------------
5.0 Collapse Code
-----------------------------*/
function Collapse() {
    var collapElems = document.querySelectorAll('[data-toggle=collapse]');
    if (collapElems) {
        Array.from(collapElems).forEach(function (collapse) {
            var targetElem = document.querySelector(collapse.getAttribute("data-target"));
            collapse.onclick = function (e) {
                var collapActive = Array.from(collapElems).reduce(function (active, collap) {
                    if (collap.classList.contains('active')) {
                        active = collap
                    }
                    return active;
                }, '');
                window.onclick = function (e) {
                    var collapActive = Array.from(collapElems).reduce(function (active, collap) {
                        if (collap.classList.contains('active')) {
                            active = collap
                        }
                        return active;
                    }, '');
                    if (!getParent(e.target, '[data-toggle=collapse]')) {
                        if (collapActive) {
                            collapActive.classList.remove('active');
                            collapActive.querySelector(collapActive.getAttribute("data-target")).classList.add('collapse');
                        }
                    }
                }
                var tipSelector = collapse.getAttribute('data-tip');
                if (tipSelector) {
                    targetElem.classList.add(tipSelector);
                }
                var hiddenEl = document.querySelector(collapse.getAttribute('data-hidden'));
                var iconEl = collapse.querySelector('[data-toggle=icon] i');
                if (targetElem.classList.contains('collapse')) {
                    collapse.classList.add('active');
                    targetElem.classList.remove('collapse');
                    if (iconEl) {
                        iconEl.classList.add('fa-minus');
                        iconEl.classList.remove('fa-plus')
                    }
                    if (hiddenEl) {
                        hiddenEl.classList.add('collapse');
                    }
                    if (collapActive) {
                        var hidItemActiv = collapActive.querySelector(collapActive.getAttribute('data-hidden'));
                        if (hidItemActiv) {
                            hidItemActiv.classList.remove('collapse');
                        }
                        var iconElActive = collapActive.querySelector('[data-toggle=icon] i');
                        if (iconElActive) {
                            iconElActive.classList.remove('fa-minus');
                            iconElActive.classList.add('fa-plus')
                        }
                        collapActive.classList.remove('active');
                        collapActive.querySelector(collapActive.getAttribute("data-target")).classList.add('collapse');
                    }
                } else {
                    if (targetElem.id !== 'search-form') {
                        collapse.classList.remove('active');
                        targetElem.classList.add('collapse');
                    }
                    if (iconEl) {
                        iconEl.classList.remove('fa-minus');
                        iconEl.classList.add('fa-plus')
                    }
                }
            }
        })
    }
}
Collapse()

/*-----------------------------
6.0 Show Sidebar Admin
-----------------------------*/
SideAdmin({
    side: '#sidebar',
    button: '#nav-sidebar',
    wrapper: '#wrapper-area',
    footer: '#footer-area'
})

function SideAdmin(options) {
    var sidebarElem = document.querySelector(options.side);
    var wrapperElem = document.querySelector(options.wrapper);
    var buttonElem = document.querySelector(options.button);
    var footerElem = document.querySelector(options.footer);
    if (buttonElem) {
        var count = 1;
        if (sessionStorage['sidebar'] === 'collapse') {
            var stSide = 'display: inline-block;';
            var stWrap = 'width: calc(100% - 260px); margin-left: 260px;';
            if (sidebarElem) {
                sidebarElem.setAttribute("style", stSide);
            }

            if (wrapperElem) {
                wrapperElem.setAttribute("style", stWrap);
            }

            if (footerElem) {
                footerElem.setAttribute("style", stWrap);
            }
            count = 2;
        }
        buttonElem.onclick = function () {
            var stSide = 'display: inline-block;';
            var stWrap = 'width: calc(100% - 260px); margin-left: 260px;';
            if (sessionStorage['sidebar'] !== 'collapse') {
                sessionStorage.setItem("sidebar", 'collapse');
            } else {
                count == 2;
            }

            if (count == 2) {
                sessionStorage.removeItem("sidebar");

                var stSide = 'display: none;';
                var stWrap = 'width: 100%; margin-left: 0;';
                count = 0;
            }
            if (sidebarElem) {
                sidebarElem.setAttribute("style", stSide);
            }

            if (wrapperElem) {
                wrapperElem.setAttribute("style", stWrap);
            }

            if (footerElem) {
                footerElem.setAttribute("style", stWrap);
            }
            count++;
        }
    }
}

/*-----------------------------
7.0 Canvas chart javascript code
-----------------------------*/
LineChart({
    canvas: '#monthly-chart',
    box: '.chart-item',
    month: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
    year: ['2020', '2021'],
    colorLine: ['#ff0000', '#0000ff'],
    values: getValChart()
})

function getValChart() {
    var dataParentEl = document.getElementById('monthly-chart');
    if (dataParentEl) {
        var dataParent = dataParentEl.parentElement.getAttribute("data-value");
        if (dataParent) {
            var arrVal = dataParent.split('%');
            for (var i = 0; i < arrVal.length; i++) {
                arrVal[i] = arrVal[i].split(',');
                for (var j = 0; j < arrVal[i].length; j++) {
                    arrVal[i][j] = parseFloat(arrVal[i][j]);
                }
            }
            return arrVal;
        }
    }
}

function LineChart(options) {
    var canvas = document.querySelector(options.canvas);
    var compare = 0;
    if (canvas) {
        var parentElem = getParent(canvas, options.box);
        canvas.width = parentElem.clientWidth - getStyle(parentElem, 'padding-left') * 2;
        canvas.height = parentElem.clientHeight - getStyle(parentElem, 'padding-top') * 2
        var ctx = canvas.getContext('2d');
        ctx.lineJoin = 'round';
        var w = canvas.width,
            h = canvas.height,
            offsetX = canvas.getBoundingClientRect().left,
            offsetY = canvas.getBoundingClientRect().top;

        // Find values max in monthly sales        
        var maxVal = Math.ceil(options.values.reduce(function (tmp, val) {
            val.reduce(function (cur, v) {
                if (v > compare) {
                    compare = v;
                    cur = compare
                }
                return cur;
            }, 0);
            tmp = compare;
            return tmp;
        }, 0) / 100).toFixed() * 100;

        var unitY = parseFloat((h - 100) / maxVal);
        var yWidth = ctx.measureText(maxVal).width + 25;
        var xlastWidth = ctx.measureText(options.month[options.month.length]).width;
        var marBot = parseFloat((h - 100) / (maxVal / 100));
        var marLeft = parseFloat((w - yWidth - xlastWidth) / (options.month.length - 1));

        var count = 0,
            pointChart = [];
        drawBackground();
        options.values.forEach(function (value) {
            pointChart[count] = [];
            for (var i = 0; i < value.length; i++) {
                pointChart[count][i] = new pointLine(marLeft * i + yWidth, h - 70 - (unitY * value[i]), 5, value[i], options.year[count], options.colorLine[count]);
            }
            count++;
        })
        pointChart.forEach(function (point) {
            count = 0;
            for (var i = 0; i < point.length; i++) {
                count = i + 1;
                if (count >= point.length) {
                    count = point.length - 1;
                }
                drawChart(point[i], point[count], true);
            }
        })
        canvas.onmousemove = function (e) {
            e.preventDefault();
            mouseX = e.clientX - offsetX;
            mouseY = e.clientY - offsetY;
            ctx.clearRect(0, 0, parentElem.clientWidth, parentElem.clientHeight);
            drawBackground();
            pointChart.forEach(function (point) {
                count = 0;
                for (var i = 0; i < point.length; i++) {
                    count = i + 1;
                    if (count >= point.length) {
                        count = point.length - 1;
                    }
                    var dx = mouseX - point[i].originX;
                    var dy = mouseY - point[i].originY;
                    if (dx * dx <= point[i].radius * point[i].radius) {
                        drawChart(point[i], point[count], false);
                        displaynotify(point[i], e);
                    } else {
                        drawChart(point[i], point[count], true);
                    }
                }
            })
        }

        function drawLine(ctx, startX, startY, endX, endY, color) {
            ctx.beginPath();
            ctx.strokeStyle = color;
            ctx.lineWidth = 1;
            ctx.moveTo(startX, startY);
            ctx.lineTo(endX, endY);
            ctx.stroke();
        }

        function drawCircle(ctx, centerX, centerY, radius, angleStart, angleEnd, color) {
            ctx.beginPath();
            ctx.fillStyle = color;
            ctx.lineWidth = 1;
            ctx.moveTo(centerX, centerY);
            ctx.arc(centerX, centerY, radius, angleStart, angleEnd);
            ctx.closePath();
            ctx.fill();
        }

        function drawLabel(ctx, posX, posY, content, oX, oY, color) {
            ctx.beginPath();
            ctx.fillStyle = color;
            ctx.font = '13px sans-serif';
            var ctxH, ctxW;
            if (oX == 0) {
                ctxW = ctx.measureText(content).width;
                ctx.fillText(content, posX - ctxW / 2, posY);
            } else {
                if (oY == 0) {
                    ctx.fillText(content, posX, posY + 13 / 3);
                } else {
                    ctx.fillText(content, posX, posY);
                }
            }
            ctx.fill()
        }

        function drawArc(ctx, centerX, centerY, radius, num, angleStart, angleEnd, color) {
            ctx.beginPath();
            ctx.strokeStyle = rgbaColor(color, 0.7);
            ctx.lineWidth = num;
            ctx.moveTo(centerX, centerY);
            ctx.arc(centerX, centerY, radius, angleStart, angleEnd);
            ctx.stroke();
            ctx.fillStyle = '#fff';
            ctx.moveTo(centerX, centerY);
            ctx.arc(centerX, centerY, radius, angleStart, angleEnd);
            ctx.closePath();
            ctx.fill();
        }

        function drawBackground() {
            if (maxVal / 100 <= Math.ceil(h / 30)) {
                for (var i = 0; i <= maxVal / 100; i++) {
                    drawLine(ctx, yWidth, marBot * i + 30, w - yWidth, marBot * i + 30, 'rgba(202,202,202,0.7)');
                    drawLabel(ctx, 0, h - 70 - marBot * i, 100 * i, 1, 0, 'gray')
                }
            } else {
                marBot = parseFloat((h - 100) / (Math.ceil(h / 30)));
                for (var i = 0; i <= Math.ceil(h / 30); i++) {
                    drawLine(ctx, yWidth, marBot * i + 30, w - yWidth, marBot * i + 30, 'rgba(202,202,202,0.7)');
                    drawLabel(ctx, 0, h - 70 - marBot * i, 100 * i, 1, 0, 'gray')
                }
            }
            var widthtmp = 0
            var toplabelWidth = options.year.reduce(function (wid, year, ind) {
                widthtmp += ctx.measureText(year).width;
                return wid = widthtmp + 40 * ind;
            }, 0)

            for (var i = 0; i < options.year.length; i++) {
                var labWidth = ctx.measureText(options.year[i]).width + 40;
                ctx.beginPath();
                ctx.fillStyle = options.colorLine[i];
                ctx.fillRect(w / 2 - yWidth - toplabelWidth / options.year.length + labWidth * i, 8, 20, 2);
                ctx.font = '13px sans-serif';
                ctx.fillText(options.year[i], w / 2 - yWidth - toplabelWidth / options.year.length + 25 + labWidth * i, 15);
            }

            for (var i = 0; i < options.month.length; i++) {
                drawLine(ctx, marLeft * i + yWidth, 30, marLeft * i + yWidth, h - 70, 'rgba(202,202,202,0.7)')
                drawLabel(ctx, marLeft * i + yWidth, h - 50, options.month[i], 0, 1, 'black')
            }
        }

        function drawChart(pStart, pEnd, cir) {
            drawLine(ctx, pStart.originX, pStart.originY, pEnd.originX, pEnd.originY, pStart.color);
            if (cir === true) {
                drawCircle(ctx, pStart.originX, pStart.originY, 5, 0, Math.PI * 2, pStart.color)
            } else {
                drawArc(ctx, pStart.originX, pStart.originY, 5, 3, 0, Math.PI * 2, pStart.color)
            }
        }

        function displaynotify(s, e) {
            ctx.fillStyle = rgbaColor(s.color, 0.6);
            var mar = (ctx.measureText(s.tip).width + 15) / 2;
            var offsetCompareX = w - yWidth - xlastWidth - e.clientX - mar;
            var offsetCompareY = e.clientY - offsetY - 65;
            console.log(offsetCompareY)
            if (offsetCompareY < 0) {
                posY = s.originY + s.radius;
            } else {
                posY = s.originY - s.radius - 35;
            }

            if (offsetCompareX < 0) {
                posX = s.originX - mar * 2 - 10
            } else {
                if (e.clientX - offsetX - yWidth < mar) {
                    posX = s.originX + 10;
                } else {
                    posX = s.originX - mar;
                }
            }
            ctx.fillRect(posX, posY, mar * 2, 30);

            ctx.fillStyle = '#000';
            ctx.font = '13px sans-serif';
            ctx.fillText(s.tip, posX + 5, posY + 19);
        }

        function pointLine(x, y, radius, val, year, color) {
            this.originX = x;
            this.originY = y;
            this.radius = radius;
            this.tip = 'Sales: ' + val + '$ - ' + year;
            this.color = color
        }
    }
}

PieChart({
    canvas: '#category-chart',
    parent: '.chart-item',
    cate: ['Clothes', 'Shoes', 'Eyewear', 'Accessories'],
    values: getValPie(),
    colors: ['#ee2222', '#13762f', '#1926bb', '#19bbb6']
})
function getValPie() {
    var dataPieEl = document.getElementById('category-chart');
    if (dataPieEl) {
        var dataPie = dataPieEl.parentElement.getAttribute('data-value');
        if (dataPie) {
            dataArrPie = dataPie.split(',');
            for (var i = 0; i < dataArrPie.length; i++) {
                dataArrPie[i] = parseFloat(dataArrPie[i])
            }
            return dataArrPie;
        }
    }
}

function PieChart(options) {
    var canvas = document.querySelector(options.canvas);
    if (canvas) {
        var ctx = canvas.getContext('2d');
        ctx.lineJoin = 'round';
        var parentElem = getParent(canvas, options.parent);
        canvas.width = parentElem.clientWidth - getStyle(parentElem, 'padding-left') * 2;
        canvas.height = parentElem.clientHeight - getStyle(parentElem, 'padding-top') * 2;

        var w = canvas.width,
            h = canvas.height,
            offsetX = canvas.getBoundingClientRect().left,
            offsetY = canvas.getBoundingClientRect().top;
        var totalVal = options.values.reduce(function (tol, val) {
            tol += val;
            return tol;
        }, 0);

        function piePoint(oX, oY, r, begin, end, col, per, data, cate) {
            this.data = data;
            this.cate = cate;
            this.percent = per;
            this.originX = oX;
            this.originY = oY;
            this.radius = r;
            this.beginRadian = begin;
            this.endRadian = end;
            this.color = col;
            this.highlightedColor = rgbaColor(this.color, .3);
            this.rr = r * r;
            this.distance = 10;
            this.centerX = oX;
            this.centerY = oY;
            this.colorTmp = col;
            this.mouse = false;
            this.popped = false;
            this.wrapped = false;
            this.midAngle = this.beginRadian + (this.endRadian - this.beginRadian) / 2;
        }

        var i, r, ount = 0,
            compare = 0,
            points = [];
        canvas.height >= canvas.width ? r = canvas.width : r = canvas.height;
        for (i = 0; i < options.cate.length; i++) {
            count = i - 1;
            var start = end;
            if (count < 0) {
                start = 0;
            }
            var per = parseFloat(((options.values[i] / totalVal) * 100).toFixed(2));
            var end = start + Math.PI * 2 * per / 100;
            points[i] = new piePoint(r / 2, r / 2, (r - 60) / 2, start, end, options.colors[i], per, options.values[i], options.cate[i]);
            if (count === options.cate.length - 1) {
                return count = 0;
            }
        }

        canvas.onmousemove = function (e) {
            e.preventDefault();
            mouseX = e.clientX - offsetX;
            mouseY = e.clientY - offsetY;
            ctx.clearRect(0, 0, parentElem.clientWidth - getStyle(parentElem, 'padding-left') * 2, parentElem.clientHeight - getStyle(parentElem, 'padding-top') * 2);
            points.forEach(function (p) {
                p.mouse = false;
                p.popped = false;
                var dx = mouseX - p.originX;
                var dy = mouseY - p.originY;
                var angle = (Math.atan2(dy, dx) + Math.PI * 2) % (Math.PI * 2);
                if (angle >= p.beginRadian && angle <= p.endRadian && dx * dx + dy * dy < p.rr || p.popped === true) {
                    p.color = p.highlightedColor;
                    p.popped = true;
                    if (canvas.width >= p.originX + p.radius + p.maxTextWidth + 30) {
                        p.centerX = p.originX + p.distance * Math.cos(p.midAngle);
                        p.centerY = p.originY + p.distance * Math.sin(p.midAngle);
                    } else {
                        p.centerX = canvas.width / 2 + p.distance * Math.cos(p.midAngle);
                        p.centerY = canvas.height / 2 + p.distance * Math.sin(p.midAngle);
                    }
                } else {
                    p.color = p.colorTmp;
                }
                drawPie(p);
                showLabel(p);
                if (p.popped === true) {
                    showData(p)
                }
                count++;
                if (count == points.length) {
                    return count = 0;
                }
            })
        }

        count = 0;
        points.forEach(function (p) {
            showLabel(p);
            drawPie(p);
            count++;
            if (count == points.length) {
                return count = 0;
            }
        })

        function drawPie(s) {
            if (canvas.width >= s.originX + s.radius + s.maxTextWidth + 30) {
                if (s.popped === true) {
                    ctx.fillStyle = s.highlightedColor;
                    posX = s.centerX;
                    posY = s.centerY;
                } else {
                    posX = s.originX;
                    posY = s.originY;
                }
            } else {
                if (s.popped === true) {
                    ctx.fillStyle = s.highlightedColor;
                    posX = s.centerX;
                    posY = s.centerY;
                } else {
                    ctx.fillStyle = s.color;
                    s.centerX = canvas.width / 2;
                    s.centerY = canvas.height / 2;
                    posY = s.centerY;
                    posX = s.centerX;
                }
            }
            ctx.beginPath();
            ctx.moveTo(posX, posY);
            ctx.arc(posX, posY, s.radius, s.beginRadian, s.endRadian);
            ctx.closePath();
            ctx.fill();
            ctx.strokeStyle = '#fff';
            ctx.lineWidth = 2;
            ctx.moveTo(posX, posY);
            ctx.arc(posX, posY, s.radius, s.beginRadian, s.endRadian);
            ctx.stroke();
            if (s.popped === true) {
                ctx.fillStyle = '#000';
            } else {
                ctx.fillStyle = '#fff';
            }
            ctx.font = r * 4 / 100 + 'px sans-serif';
            text = s.percent + '%';
            ctx.fillText(text, posX + s.radius * Math.cos(s.midAngle) / 2, posY + s.radius * Math.sin(s.midAngle) / 2);
        }

        function showLabel(s) {
            ctx.beginPath();
            ctx.fillStyle = s.color;
            ctx.font = '13px sans-serif';
            s.textWidth = 0;
            var counttmp = 1;
            var tolTextWidth = points.reduce(function (val, item, ind) {
                val += ctx.measureText(item.cate).width + 30 - (15 / (points.length));
                if (counttmp < points.length) {
                    points[counttmp].textWidth = val + 15 / (points.length);
                }
                counttmp++;
                return val;
            }, 0)
            compare = 0;
            var maxTextWidth = points.reduce(function (val, item, ind) {
                if (compare < ctx.measureText(item.cate).width + 50) {
                    compare = ctx.measureText(item.cate).width + 50;
                    val = compare;
                }
                return val;
            }, 0)
            if (canvas.width >= (s.originX + s.radius + maxTextWidth) + 20) {
                posY = s.originY - (30 * points.length) / 2 + 30 * count;
                posX = s.originX + s.radius + maxTextWidth / 2;
            } else {
                posY = canvas.height / 2 + s.radius + 15;
                posX = s.centerX - tolTextWidth / 2 + s.textWidth + 15 / (points.length - 1);
            }
            s.maxTextWidth = maxTextWidth;
            s.tolTextWidth = tolTextWidth;
            ctx.fillRect(posX, posY, 10, 10);
            ctx.fillText(s.cate, posX + 15, posY + 10);
            ctx.closePath();
            ctx.fill();
        }

        function showData(s) {
            if (s.popped === true) {
                posX = s.centerX;
                posY = s.centerY;
            } else {
                posX = s.originX;
                posY = s.originY;
            }

            if (s.centerY >= s.originY) {
                posY = s.radius + 30;
            }
            ctx.fillStyle = s.colorTmp;
            var marTmp = (ctx.measureText(s.cate + ' - ' + s.data + '$ (' + s.percent + '%)').width + 15) / 2;
            ctx.fillRect(posX - marTmp, posY - s.radius - 20, 10, 10);
            ctx.fillStyle = '#000';
            ctx.font = '13px sans-serif';
            ctx.fillText(s.cate + ' - ' + s.data + '$ (' + s.percent + '%)', posX - marTmp + 15, posY - s.radius - 10);
        }
    }
}

function rgbaColor(color, alpha) {
    var r = parseInt(color.slice(1, 3), 16),
        g = parseInt(color.slice(3, 5), 16),
        b = parseInt(color.slice(5, 7), 16);
    if (alpha) {
        return 'rgba(' + r + ', ' + g + ', ' + b + ', ' + alpha + ')';
    } else {
        return 'rgb(' + r + ', ' + g + ', ' + b + ')';
    }
}

function colorRandom() {
    var letter = '0123456789abcdef'.split('');
    var color = '#';
    for (var i = 0; i < 6; i++) {
        color += letter[Math.floor(Math.random() * 16)];
    }
    return color;
}


/*-----------------------------
8.0 Tab Navigation JS Code
-----------------------------*/
function TabNavigation() {
    var tabEls = document.querySelectorAll('[data-toggle=tab]');
    if (tabEls) {
        var targEls = Array.from(tabEls).reduce(function (act, nav, ind) {
            act[ind] = getParent(document.querySelector(nav.getAttribute('data-target')), '.form-item');
            return act;
        }, []);
        Array.from(tabEls).forEach(function (tab) {
            var targEl = document.querySelector(tab.getAttribute('data-target'));
            tab.onclick = function (e) {
                e.preventDefault();
                var navAct = Array.from(tabEls).reduce(function (act, nav) {
                    if (nav.classList.contains('active')) {
                        act = nav;
                    }
                    return act;
                }, '');
                var tabAct = targEls.reduce(function (act, tab) {
                    if (tab.classList.contains('active')) {
                        act = tab;
                    }
                    return act;
                }, '');
                navAct.classList.remove('active');
                tabAct.classList.remove('active');
                tab.classList.add('active');
                getParent(targEl, '.form-item').classList.add('active');
            }
        })
    }
}

TabNavigation()

/*-----------------------------
9.0 Show Password JS Code
-----------------------------*/
var inputPassElems = document.querySelectorAll('input[type=password]');
if (inputPassElems) {
    var count = 0;
    Array.from(inputPassElems).forEach(function (inp) {
        var eyeshowEl = getParent(inp, '.form-group').querySelector('.eye-show');
        if (eyeshowEl) {
            eyeshowEl.onclick = function (e) {
                console.log(eyeshowEl)
                e.preventDefault();
                inp.type = 'text';
                eyeshowEl.querySelector('.eye-show i').classList.remove('fa-eye-slash');
                eyeshowEl.querySelector('.eye-show i').classList.add('fa-eye');
                if (count > 0) {
                    inp.type = 'password';
                    eyeshowEl.querySelector('.eye-show i').classList.add('fa-eye-slash');
                    eyeshowEl.querySelector('.eye-show i').classList.remove('fa-eye');
                    count = -1;
                }
                count++;
            }
        }
    })
}

/*-----------------------------
10.0 Validator Form JS Code
-----------------------------*/
function Validator(options) {
    function getParent(element, selector) {
        while (element.parentElement) {
            if (element.parentElement.matches(selector)) {
                return element.parentElement;
            }
            element = element.parentElement;
        }
    }

    // Tập hợp tất cả các lỗi trên đối tượng formElement
    var selectorRules = {};
    // Hàm thực hiện validate
    function validate(inputElement, rule) {
        var errorElement = getParent(inputElement, options.formGroupSelector).querySelector(options.errorSelector);
        var errorMessage;
        //Lấy các rules của selector
        var rules = selectorRules[rule.selector];

        //Lặp qua từng rule và kiểm tra
        for (var i = 0; i < rules.length; ++i) {
            switch (inputElement.type) {
                case 'radio':
                case 'checkbox':
                    errorMessage = rules[i](
                        formElement.querySelector(rule.selector + ':checked')
                    );
                    break;
                case 'file':
                    errorMessage = rules[i](inputElement.files);
                    break;
                default:
                    errorMessage = rules[i](inputElement.value.trim());
            }
            if (errorMessage) break;
        }

        if (errorElement) {
            if (errorMessage) {
                errorElement.innerText = errorMessage;
                getParent(inputElement, options.formGroupSelector).classList.add('invalid');

            } else {
                errorElement.innerText = '';
                getParent(inputElement, options.formGroupSelector).classList.remove('invalid');
            }
        }

        return !errorMessage;
    }
    // Lấy form cần validate
    var formElement = document.querySelector(options.form);
    if (formElement) {
        formElement.onsubmit = function (e) {
            e.preventDefault();

            var isFormValid = true;

            //Lặp lại từng input và validate
            options.rules.forEach(function (rule) {
                var inputElements = formElement.querySelectorAll(rule.selector);
                Array.from(inputElements).forEach(function (inputElement) {

                    var isValid = validate(inputElement, rule);

                    if (!isValid) {
                        isFormValid = false;
                    }
                })
            });
            if (isFormValid) {
                if (typeof options.onSubmit === 'function') {
                    var formInputs = formElement.querySelectorAll('[name]');
                    var formDatas = Array.from(formInputs).reduce(function (values, input) {
                        switch (input.type) {
                            case 'checkbox':
                                if (!input.matches(':checked')) {
                                    values[input.name] = '';
                                    return values;
                                }
                                if (!Array.isArray(values[input.name])) {
                                    values[input.name] = [];
                                }
                                values[input.name].push(input.value);
                                break;
                            case 'radio':
                                values[input.name] = formElement.querySelector('input[name="' + input.name + '"]:checked').value;
                                break;
                            case 'file':
                                values[input.name] = input.files;
                                break;
                            default:
                                values[input.name] = input.value;
                        }
                        return values;
                    }, {});
                    options.onSubmit(formDatas);
                }
                // Trường hợp submit với hành vi mặc định
                else {
                    if (formElement.matches('form')) {
                        formElement.submit();
                    } else {
                        formElement.querySelector('form').submit();
                    }
                }
            }
        }

        // Lặp mỗi input và xử lý sự kiện (onblur, oninput,...)
        options.rules.forEach(function (rule) {
            // Lưu lại các rules cho mỗi input
            if (Array.isArray(selectorRules[rule.selector])) {
                selectorRules[rule.selector].push(rule.test);
            } else {
                selectorRules[rule.selector] = [rule.test];
            }

            var inputElements = formElement.querySelectorAll(rule.selector);
            Array.from(inputElements).forEach(function (inputElement) {
                if (inputElement) {
                    inputElement.onblur = function (e) {
                        validate(inputElement, rule);
                    }

                    inputElement.onfocus = function () {
                        var errorElement = getParent(inputElement, options.formGroupSelector).querySelector(options.errorSelector);
                        if (errorElement) {
                            errorElement.innerText = '';
                        }
                        getParent(inputElement, options.formGroupSelector).classList.remove('invalid');
                    }

                    inputElement.oninput = function () {
                        var errorElement = getParent(inputElement, options.formGroupSelector).querySelector(options.errorSelector);
                        if (errorElement) {
                            errorElement.innerText = '';
                        }
                        getParent(inputElement, options.formGroupSelector).classList.remove('invalid');

                        if (inputElement.id === 'email_address') {
                            var PassPar = formElement.querySelector('#creatElPass');
                            var dataCuss = PassPar.getAttribute('data-customers');
                            var dataMail = inputElement.value;
                            if (dataCuss) {
                                var dataARR = dataCuss.split(',');
                                var passMail;
                                dataARR.forEach(function (data) {
                                    if (dataMail.toLowerCase() === data.split('-')[0].trim().toLowerCase()) {
                                        passMail = data.split('-')[1].trim();
                                    }
                                })
                                if (passMail) {
                                    Array.from(PassPar.querySelectorAll('input[name]')).forEach(function (chel) {
                                        PassPar.removeChild(getParent(chel, '.form-group'));
                                    })
                                    createGroEl('pass-oldest', 'pass-oldest', 'Password');
                                    PassPar.setAttribute('data-pass', passMail);
                                }
                            } else {
                                Array.from(PassPar.querySelectorAll('input[name]')).forEach(function (chel) {
                                    PassPar.removeChild(getParent(chel, '.form-group'));
                                })
                                createGroEl('pass-order', 'pass-order', 'Password');
                                createGroEl('rePass-order', 'rePass-order', 'Re-Password');
                            }
                            function createGroEl(idEl, nameInEl, texEl) {
                                var grel = CreateEl('div', 'form-group', '', '', '');
                                var label = CreateEl('label', '', texEl, '', '');
                                grel.appendChild(label);
                                var eyeGrEl = CreateEl('div', 'pass-eye', '', '', '');
                                var inpEl = CreateEl('input', 'form-control', '', idEl, nameInEl);
                                inpEl.type = 'password';
                                eyeGrEl.appendChild(inpEl);
                                var eyeEl = CreateEl('span', 'eye-show', '', '', '');
                                var iconEl = CreateEl('i', 'fa', '', '', '');
                                iconEl.classList.add('fa-eye-slash');
                                eyeEl.appendChild(iconEl);
                                eyeGrEl.appendChild(eyeEl);
                                grel.appendChild(eyeGrEl);
                                var messEl = CreateEl('div', 'form-message', '', '', '');
                                grel.appendChild(messEl);
                                PassPar.appendChild(grel);
                            }
                            function CreateEl(tagElName, classElName, textEl, idEl, nameEl) {
                                var elCr = document.createElement(tagElName);
                                if (classElName.trim() !== '') {
                                    elCr.classList.add(classElName);
                                }
                                if (textEl.trim() !== '') {
                                    const teEl = document.createTextNode(textEl);
                                    elCr.appendChild(teEl);
                                }
                                if (idEl.trim() !== '') {
                                    elCr.id = idEl;
                                }
                                if (nameEl.trim() !== '') {
                                    elCr.name = idEl;
                                }
                                return elCr;
                            }
                        }
                    }

                    if (inputElement.type === 'file') {
                        if (inputElement.files.length == 0) {
                            var imgPreview = getParent(inputElement, options.formGroupSelector).querySelector(options.viewSelector).children;
                            if (imgPreview.length == 0) {
                                var errorElement = getParent(inputElement, options.formGroupSelector).querySelector(options.errorSelector);
                                errorElement.innerText = 'No file chosen !';
                                getParent(inputElement, options.formGroupSelector).classList.add('invalid');
                            }
                        }

                        inputElement.onchange = function (e) {
                            validate(inputElement, rule);
                            var checkError = validate(inputElement, rule);
                            if (checkError) {
                                var viewElem = getParent(inputElement, options.formGroupSelector).querySelector(options.viewSelector);
                                var widView = getStyle(viewElem, 'width');
                                Array.from(viewElem.querySelectorAll('img')).forEach(function (img) {
                                    viewElem.removeChild(img);
                                });

                                Array.from(inputElement.files).forEach(function (file) {
                                    const reader = new FileReader();
                                    reader.onload = function (e) {
                                        const img = document.createElement('img');
                                        img.src = e.target.result;
                                        img.style.width = widView / inputElement.files.length + 'px';
                                        img.style.flex = '0 0 ' + 100 / inputElement.files.length + '%';
                                        img.alt = `file - ${++count}`;
                                        viewElem.appendChild(img)
                                    }
                                    reader.readAsDataURL(file)
                                })
                            }
                        }
                    }
                }
            });
        });
    }
}

// Definition of rules
Validator.isRequired = function (selector, message) {
    return {
        selector: selector,
        test: function (value) {
            return value ? undefined : message || 'Vui lòng nhập trường này !';
        }
    };
}

Validator.isEmail = function (selector, message) {
    return {
        selector: selector,
        test: function (value) {
            var regex = /^[a-zA-Z0-9.!#$%&'*+/=?^_`{|}~-]+@[a-zA-Z0-9-]+(?:\.[a-zA-Z0-9-]+)*$/;
            return regex.test(value) ? undefined : message || 'Email không hợp lệ !';
        }
    };
}

Validator.minLength = function (selector, min, message) {
    return {
        selector: selector,
        test: function (value) {
            return value.length >= min ? undefined : message || `Vui lòng nhập tối thiêu ${min} ký tự !`;
        }
    };
}

Validator.compareValues = function (selector, getCompareValue, message) {
    return {
        selector: selector,
        test: function (value) {
            if (selector !== '#pass-oldest') {
                return value === getCompareValue() ? undefined : message || 'Gía trị bạn nhập không khớp !';
            } else {
                return MD5(value) === getCompareValue() ? undefined : message || 'Gía trị bạn nhập không khớp !';
            }
        }
    };
}

Validator.fileRequired = function (selector, message) {
    return {
        selector: selector,
        test: function (files) {
            return files.length ? undefined : message;
        }
    }
}

Validator.fileType = function (selector, typeArray, message) {
    return {
        selector: selector,
        test: function (files) {
            for (var i = 0; i < files.length; i++) {
                if (!typeArray.includes(files[i].type)) {
                    return message;
                }
            }
        }
    }
}

Validator.fileSize = function (selector, size, message) {
    return {
        selector: selector,
        test: function (files) {
            for (var i = 0; i < files.length; i++) {
                if (files[i].size > size) {
                    return message;
                }
            }
        }
    }
}

Validator.isPhone = function (selector, message) {
    return {
        selector: selector,
        test: function (value) {
            var regex = /^[\+]?[(]?[0-9]{3}[)]?[-\s\.]?[0-9]{3}[-\s\.]?[0-9]{4,6}$/im;
            return regex.test(value) ? undefined : message || 'Số điện thoại không hợp lệ !';
        }
    };
}

function formReset() {
    var formEls = document.forms;
    if (formEls) {
        Array.from(formEls).forEach(function (formEl) {
            var resetEl = formEl.querySelector('[type=reset]');
            if (resetEl) {
                resetEl.onclick = function (e) {
                    e.preventDefault();
                    var inputEls = formEl.querySelectorAll('input[name]');
                    if (inputEls) {
                        Array.from(inputEls).forEach(function (inp) {
                            switch (inp.type) {
                                case 'number': inp.value = '0'; break;
                                default: inp.value = '';
                            }
                        })
                    }
                }
            }
        })
    }
}
formReset()

/*-----------------------------
11.0 Modal Code
-----------------------------*/
function Modal() {
    var modalEls = document.querySelectorAll('[data-toggle=modal]');
    if (modalEls) {
        Array.from(modalEls).forEach(function (mod) {
            mod.onclick = function (e) {
                e.preventDefault();
                var tarEl = document.querySelector(mod.getAttribute('data-target'));
                var hidEl = document.querySelector(mod.getAttribute('data-hidden'));
                tarEl.style.display = 'flex';
                tarEl.querySelector('.modal-dialog').style.animationDuration = '500ms';
                tarEl.querySelector('.modal-dialog').classList.add('showInUp');
                document.body.style.overflowY = 'hidden';
                if (hidEl) {
                    hidEl.classList.add('collapse');
                }
                Scroll(mod);
                var buttclos = tarEl.querySelector('[data-dismiss=modal]');
                if (buttclos) {
                    buttclos.onclick = function (e) {
                        tarEl.style.display = 'none';
                        if (hidEl) {
                            hidEl.classList.remove('collapse');
                        }
                    }
                }
                tarEl.onclick = function (e) {
                    if (!getParent(e.target, '.modal-dialog')) {
                        tarEl.style.display = 'none';
                        if (hidEl) {
                            hidEl.classList.remove('collapse');
                        }
                    }
                    tarEl.querySelector('.modal-dialog').classList.remove('showInUp');
                    document.body.style.overflowY = 'visible';
                }
                var dataName = mod.getAttribute('data-name');
                var showNamEl = tarEl.querySelector('[data-type=show-name]');
                if (showNamEl && showNamEl.tagName == 'INPUT') {
                    showNamEl.value = dataName;
                } else {
                    if (dataName && showNamEl) {
                        showNamEl.innerText = dataName;
                    }
                }
                var dataInfo = mod.getAttribute('data-info');
                if (dataInfo) {
                    var prdInfor = dataInfo.split('**');
                    prdInfor.push(mod.getAttribute('data-detail'))
                    tarEl.querySelector('.quickview-image img').src = 'admin/images/product/' + prdInfor[2];
                    var contentElModal = tarEl.querySelector('.quickview-description');
                    if (contentElModal) {
                        var ratProEl = contentElModal.querySelector('.prd-rating .stars');
                        if (ratProEl) {
                            var widthStars = parseFloat(parseFloat(prdInfor[3]) / 5) * 100;
                            document.styleSheets[0].deleteRule('.stars:after {width: 100%}', 0);
                            document.styleSheets[0].insertRule('.stars:after {width:' + widthStars + '%}', 0);
                            contentElModal.querySelector('.prd-rating .stars-show').innerText = '(' + prdInfor[3] + ')'
                        }

                        contentElModal.querySelector('h4').innerText = prdInfor[1];
                        contentElModal.querySelector('.prd-details').innerText = prdInfor[7];
                        if (parseFloat(prdInfor[5]) > 0) {
                            contentElModal.querySelector('.price-current').innerText = '$' + format(parseFloat(prdInfor[4]) * (100 - parseFloat(prdInfor[5])) / 100);
                            contentElModal.querySelector('.price-oldest').innerText = '$' + format(prdInfor[4]);
                        } else {
                            contentElModal.querySelector('.price-current').innerText = '$' + format(prdInfor[4]);
                        }
                        contentElModal.querySelector('a').href = 'index.php?page_layout=prd_details&prd_id=' + parseInt(prdInfor[0]);
                    }
                    var formElModal = tarEl.querySelector('.quickview-form');
                    if (formElModal) {
                        formElModal.querySelector('input[name=prd_id]').value = parseInt(prdInfor[0]);
                        formElModal.querySelector('input[name=quantity]').max = parseInt(prdInfor[6]);
                        formElModal.querySelector('.modal-wishlist a').href = 'index.php?page_layout=wishlist&prd_id=' + parseInt(prdInfor[0]);
                        formElModal.querySelector('.modal-compare a').href = 'index.php?page_layout=compare&prd_id=' + parseInt(prdInfor[0]);
                    }
                }

                var dataSub = mod.getAttribute('data-href');
                var subEl = tarEl.querySelector('a[data-submit=modal]');
                if (subEl && dataSub) {
                    subEl.href = dataSub;
                }
            }
        })
    }
}

Modal()

/*-----------------------------
12.0 Scroll Function Code
-----------------------------*/
function Scroll(element) {
    var positionY = element.getBoundingClientRect().top + window.pageYOffset;
    window.scrollTo({
        top: positionY,
        behavior: 'smooth'
    })
}
/*-----------------------------
13.0 Set Time Wait Code
-----------------------------*/
function Wait() {
    var waitEls = document.querySelectorAll('[data-toggle=wait]');
    if (waitEls) {
        Array.from(waitEls).forEach(function (wait) {
            var time = parseInt(wait.getAttribute('data-session'));
            var timeout = time;
            var interval;
            if (timeout) {
                interval = setInterval(function () {
                    timeout -= 1;
                    wait.innerHTML = timeout;
                    if (timeout <= 0) {
                        clearInterval(interval);
                        window.location.reload(true);
                    }
                }, 1000)
            } else {
                clearInterval(interval)
            }
        })
    }
}
setTimeout(Wait(), 1000)

/*-----------------------------
14.0 Check All JS Code
-----------------------------*/
function checkAll() {
    var allEls = document.querySelectorAll('[data-toggle=checkall]');;
    if (allEls) {
        Array.from(allEls).forEach(function (all) {
            all.onclick = function (e) {
                var items = getParent(e.target, 'form').querySelectorAll(all.getAttribute("data-target"));
                if (e.target.matches(':checked')) {
                    Array.from(items).forEach(function (input) {
                        input.checked = true;
                    })
                } else {
                    Array.from(items).forEach(function (input) {
                        input.checked = false;
                    })
                }
            }
        })
    }
}

checkAll()

/*-----------------------------
15.0 Create Option Element JS Code
-----------------------------*/
function GetData() {
    var sendEls = document.querySelectorAll('[data-toggle=movedata]');
    if (sendEls) {
        Array.from(sendEls).forEach(function (send) {
            var tmpData = send.getAttribute('data-catego');
            var ArrayData = tmpData.split(';');
            var tmpVal = [];
            var ArrID = ArrayData.reduce(function (id, dt, ind) {
                id[ind] = dt.split('-').pop();
                tmpVal[ind] = dt.split('-').shift()
                return id;
            }, [])
            var ArrCall = [];
            var ArrCate = tmpVal.reduce(function (call, val, ind) {
                call[ind] = val.split(':').shift();
                ArrCall[ind] = val.split(':').pop().trim();
                return call;
            }, []);
            var ArrResult = [];
            for (var i = 0; i < ArrID.length; i++) {
                var index = ArrID[i];
                ArrResult[index] = [];
                ArrResult[index].push(ArrCall[i]);
                ArrResult[index].push(ArrCate[i]);
                ArrResult[index].push(ArrID[i]);
            }
            if (send.value) {
                var arrTest = [];
                ArrResult.forEach(function (rsl) {
                    if (parseInt(send.value) == parseInt(rsl[0])) {
                        arrTest.push(rsl);
                    }
                })
                var targetEl = getParent(send, send.getAttribute('data-parent')).querySelector(send.getAttribute('data-target'));
                targetEl.disabled = false;
                var optiontmp = targetEl.querySelectorAll('option:not(:first-child)');
                if (optiontmp) {
                    Array.from(optiontmp).forEach(function (op) {
                        targetEl.removeChild(op);
                    })
                }
                var selectCheck = parseInt(send.getAttribute('data-select'));

                if (arrTest.length > 0) {
                    arrTest.forEach(function (elem) {
                        var opEl = document.createElement('option');
                        var textOp = document.createTextNode(elem[1]);
                        opEl.appendChild(textOp);
                        targetEl.appendChild(opEl)
                        opEl.value = elem[2];
                        if (selectCheck && selectCheck == opEl.value) {
                            opEl.selected = true;
                        }
                    })
                }
            }
            send.onchange = function () {
                var arrTest = [];
                ArrResult.forEach(function (rsl) {
                    if (parseInt(send.value) == parseInt(rsl[0])) {
                        arrTest.push(rsl);
                    }
                })
                var targetEl = getParent(send, send.getAttribute('data-parent')).querySelector(send.getAttribute('data-target'));
                targetEl.disabled = false;
                var optiontmp = targetEl.querySelectorAll('option:not(:first-child)');
                if (optiontmp) {
                    Array.from(optiontmp).forEach(function (op) {
                        targetEl.removeChild(op);
                    })
                }
                if (arrTest.length > 0) {
                    arrTest.forEach(function (elem) {
                        var opEl = document.createElement('option');
                        var textOp = document.createTextNode(elem[1]);
                        opEl.appendChild(textOp);
                        targetEl.appendChild(opEl)
                        opEl.value = elem[2];
                    })
                }
            }
        })
    }
}
GetData();

/*-----------------------------
16.0 Limit Line Text JS Code
-----------------------------*/
function LimitLine() {
    var limitEls = document.querySelectorAll('[data-toggle=limit]');
    if (limitEls) {
        Array.from(limitEls).forEach(function (liEl) {
            var imgEls = liEl.querySelectorAll('img');
            if (imgEls) {
                Array.from(imgEls).forEach(function (img) {
                    var parImg = img.parentElement;
                    parImg.style.display = 'none'
                })
            }
            Array.from(liEl.children).forEach(function (chil) {
                if (chil.innerHTML === '&nbsp;') {
                    chil.parentElement.removeChild(chil)
                } else {
                    if (chil.style.textAlign === 'center') {
                        chil.parentElement.removeChild(chil)
                    }
                    chil.style.fontSize = 'inherit';
                    chil.style.marginBottom = 0;
                }
            })
            liEl.innerHTML = liEl.innerText;
            liEl.style.height = getStyle(liEl, 'line-height') * liEl.getAttribute('data-line') + 'px';
        })
    }
}
LimitLine()

/*-----------------------------
17.0 Select Color Multipart Product JS Code
-----------------------------*/
function SelectColor() {
    var selecEls = document.querySelectorAll('[data-toggle=select]');
    if (selecEls) {
        Array.from(selecEls).forEach(function (sel) {
            sel.onblur = function () {
                console.log()
                var showEl = getParent(sel, 'form').querySelector(sel.getAttribute('data-target'));
                if (showEl) {
                    if (showEl.value) {
                        showEl.value += ', ' + sel.value;
                    } else {
                        showEl.value = sel.value;
                    }
                }
            }
        })
    }
}
SelectColor();

/*-----------------------------
18.0 Show/Hide Message Box Code
------------------------------*/
function MessageBox() {
    var boxMess = document.getElementById('messenger-box');
    if (boxMess) {
        var boxBut = boxMess.querySelector('#block-button');
        if (boxBut) {
            var iconEl = boxBut.querySelector('i');
            boxBut.onclick = function () {
                if (iconEl.classList.contains('fa-angle-down')) {
                    boxMess.style.bottom = '-50vh';
                    iconEl.classList.remove('fa-angle-down');
                    iconEl.classList.add('fa-angle-up');
                    sessionStorage['boxdown'] = true;
                } else {
                    boxMess.style.bottom = '0';
                    iconEl.classList.add('fa-angle-down');
                    iconEl.classList.remove('fa-angle-up');
                    sessionStorage.removeItem('boxdown');
                }
            }
            if (sessionStorage['boxdown']) {
                boxMess.style.bottom = '-50vh';
                iconEl.classList.remove('fa-angle-down');
                iconEl.classList.add('fa-angle-up');
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