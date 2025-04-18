 
    
    $(function() {
        "use strict";
        // ============================================================== 
        // Product Sales
        // ============================================================== 

        new Chartist.Bar('.ct-chart-product', {
            labels: ['Q1', 'Q2', 'Q3', 'Q4'],
            series: [
                [800000, 1200000, 1400000, 1300000],
                [200000, 400000, 500000, 300000],
                [100000, 200000, 400000, 600000]
            ]
        }, {
            stackBars: true,
            axisY: {
                labelInterpolationFnc: function(value) {
                    return (value / 1000) + 'k';
                }
            }
        }).on('draw', function(data) {
            if (data.type === 'bar') {
                data.element.attr({
                    style: 'stroke-width: 40px'
                });
            }
        });
    });

    function getLastNDaysLabels(n) {
        const daysOfWeek = ['Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat'];
        const labels = [];
        const today = new Date();
    
        for (let i = n - 1; i >= 0; i--) {
            const date = new Date();
            date.setDate(today.getDate() - i);
            labels.push(daysOfWeek[date.getDay()]);
        }
    
        return labels;
    }




    // ============================================================== 
    // Product Category
    // ============================================================== 
    var chart = new Chartist.Pie('.ct-chart-category', {
        series: [60, 30, 30],
        labels: ['Bananas', 'Apples', 'Grapes']
    }, {
        donut: true,
        showLabel: false,
        donutWidth: 40

    });


    chart.on('draw', function(data) {
        if (data.type === 'slice') {
            // Get the total path length in order to use for dash array animation
            var pathLength = data.element._node.getTotalLength();

            // Set a dasharray that matches the path length as prerequisite to animate dashoffset
            data.element.attr({
                'stroke-dasharray': pathLength + 'px ' + pathLength + 'px'
            });

            // Create animation definition while also assigning an ID to the animation for later sync usage
            var animationDefinition = {
                'stroke-dashoffset': {
                    id: 'anim' + data.index,
                    dur: 1000,
                    from: -pathLength + 'px',
                    to: '0px',
                    easing: Chartist.Svg.Easing.easeOutQuint,
                    // We need to use `fill: 'freeze'` otherwise our animation will fall back to initial (not visible)
                    fill: 'freeze'
                }
            };

            // If this was not the first slice, we need to time the animation so that it uses the end sync event of the previous animation
            if (data.index !== 0) {
                animationDefinition['stroke-dashoffset'].begin = 'anim' + (data.index - 1) + '.end';
            }

            // We need to set an initial value before the animation starts as we are not in guided mode which would do that for us
            data.element.attr({
                'stroke-dashoffset': -pathLength + 'px'
            });

            // We can't use guided mode as the animations need to rely on setting begin manually
            // See http://gionkunz.github.io/chartist-js/api-documentation.html#chartistsvg-function-animate
            data.element.animate(animationDefinition, false);
        }
    });

    // For the sake of the example we update the chart every time it's created with a delay of 8 seconds
    


    // ============================================================== 
    // Customer acquisition
    // ============================================================== 
    var chart = new Chartist.Line('.ct-chart', {
        labels: getLastNDaysLabels(4),
        // labels: ['Mon', 'Tue', 'Wed', 'Thu'],
        series: [
            renewals,
            subscriptions

        ]
    }, {
        low: 0,
        showArea: true,
        showPoint: false,
        fullWidth: true
    });

    chart.on('draw', function(data) {
        if (data.type === 'line' || data.type === 'area') {
            data.element.animate({
                d: {
                    begin: 2000 * data.index,
                    dur: 2000,
                    from: data.path.clone().scale(1, 0).translate(0, data.chartRect.height()).stringify(),
                    to: data.path.clone().stringify(),
                    easing: Chartist.Svg.Easing.easeOutQuint
                }
            });
        }
    });




    // ============================================================== 
    // Revenue Cards
    // ============================================================== 
    $("#sparkline-revenue").sparkline(revenueArr, {
        type: 'line',
        width: '99.5%',
        height: '100',
        lineColor: '#5969ff',
        fillColor: '#dbdeff',
        lineWidth: 2,
        spotColor: undefined,
        minSpotColor: undefined,
        maxSpotColor: undefined,
        highlightSpotColor: undefined,
        highlightLineColor: undefined,
        resize: true
    });



    $("#sparkline-revenue2").sparkline(subsArr, {
        type: 'line',
        width: '99.5%',
        height: '100',
        lineColor: '#ff407b',
        fillColor: '#ffdbe6',
        lineWidth: 2,
        spotColor: undefined,
        minSpotColor: undefined,
        maxSpotColor: undefined,
        highlightSpotColor: undefined,
        highlightLineColor: undefined,
        resize: true
    });



    $("#sparkline-revenue3").sparkline(unSubArr, {
        type: 'line',
        width: '99.5%',
        height: '100',
        lineColor: '#25d5f2',
        fillColor: '#dffaff',
        lineWidth: 2,
        spotColor: undefined,
        minSpotColor: undefined,
        maxSpotColor: undefined,
        highlightSpotColor: undefined,
        highlightLineColor: undefined,
        resize: true
    });



    $("#sparkline-revenue4").sparkline(subRevArr, {
        type: 'line',
        width: '99.5%',
        height: '100',
        lineColor: '#fec957',
        fillColor: '#fff2d5',
        lineWidth: 2,
        spotColor: undefined,
        minSpotColor: undefined,
        maxSpotColor: undefined,
        highlightSpotColor: undefined,
        highlightLineColor: undefined,
        resize: true,
    });

    

    $("#renew-revenue4").sparkline(renRevArr, {
        type: 'line',
        width: '99.5%',
        height: '100',
        lineColor: '#d8083c',
        fillColor: '#dde2e4',
        lineWidth: 2,
        spotColor: undefined,
        minSpotColor: undefined,
        maxSpotColor: undefined,
        highlightSpotColor: undefined,
        highlightLineColor: undefined,
        resize: true,
    });





    // ============================================================== 
    // Total Revenue
    // ============================================================== 
    Morris.Area({
        element: 'morris_totalrevenue',
        behaveLikeLine: true,
        data: [
            { x: '2016 Q1', y: 0, },
            { x: '2016 Q2', y: 7500, },
            { x: '2017 Q3', y: 15000, },
            { x: '2017 Q4', y: 22500, },
            { x: '2018 Q5', y: 30000, },
            { x: '2018 Q6', y: 40000, }
        ],
        xkey: 'x',
        ykeys: ['y'],
        labels: ['Y'],
        lineColors: ['#5969ff'],
        resize: true

    });




    // ============================================================== 
    // Revenue By Categories
    // ============================================================== 

    var chart = c3.generate({
        bindto: "#c3chart_category",
        data: {
            columns: [
                ['Men', 100],
                ['Women', 80],
                ['Accessories', 50],
                ['Children', 40],
                ['Apperal', 20],

            ],
            type: 'donut',

            onclick: function(d, i) { console.log("onclick", d, i); },
            onmouseover: function(d, i) { console.log("onmouseover", d, i); },
            onmouseout: function(d, i) { console.log("onmouseout", d, i); },

            colors: {
                Men: '#5969ff',
                Women: '#ff407b',
                Accessories: '#25d5f2',
                Children: '#ffc750',
                Apperal: '#2ec551',



            }
        },
        donut: {
            label: {
                show: false
            }
        },



    });

