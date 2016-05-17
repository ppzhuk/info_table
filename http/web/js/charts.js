$(function () {
    var dateRangeType = 'day';
    var chart = [null, null, null, null];
    var ajax = JSON.parse($.ajax({
        url: "index.php/Chart/getMaxDate",
        async: false,
        dataType: 'json'
    }).responseText);
    var cur_date = new Date(Date.parse(
        ajax.max_date
    ));
    var start_date = new Date(cur_date.getFullYear(), cur_date.getMonth(), cur_date.getDate(), 0, 0, 0, 0);
    var end_date = new Date(cur_date.getFullYear(), cur_date.getMonth(), cur_date.getDate(), 23, 59, 59, 0);

    $("#range-day").datepicker({
        changeMonth: true,
        changeYear: true,
        changeDay: true,
        dateFormat: 'dd/mm/yy'
    });
    $("#range-day").datepicker('setDate', new Date(cur_date.getFullYear(), cur_date.getMonth(), cur_date.getDate(), 0, 0, 0, 0));

    $("#range-day").on('change', function(){
        cur_date = new Date(Date.parse($(this).datepicker('getDate')));
        start_date = new Date(cur_date.getFullYear(), cur_date.getMonth(), cur_date.getDate(), 0, 0, 0, 0);
        end_date = new Date(cur_date.getFullYear(), cur_date.getMonth(), cur_date.getDate(), 23, 59, 59, 0);
        chartAjaxLoad[0](
            start_date.getFullYear() + '-' + ((start_date.getMonth() + 1).toString().length < 2 ? '0' : '' ) + (start_date.getMonth() + 1) + '-' + (start_date.getDate().toString().length < 2 ? '0' : '' ) + start_date.getDate() + ' 00:00:00',
            end_date.getFullYear() + '-' + ((end_date.getMonth() + 1).toString().length < 2 ? '0' : '' ) + (end_date.getMonth() + 1) + '-' + (end_date.getDate().toString().length < 2 ? '0' : '' ) + end_date.getDate() + ' 23:59:59'
        );
    });

    $("#range-month, #range-month2").datepicker({
        changeMonth: true,
        changeYear: true,
        changeDay: false,
        dateFormat: 'dd/mm/yy',
        beforeShow: function(){
            $(this).val(cur_date.getDate() + '/' + (cur_date.getMonth() + 1) + '/' + cur_date.getFullYear());
        },
        onClose: function(){
            $(this).val((cur_date.getMonth() + 1) + '/' + cur_date.getFullYear());
        }
    });
    $("#range-month, #range-month2").datepicker('setDate', new Date(cur_date.getFullYear(), cur_date.getMonth(), cur_date.getDate(), 0, 0, 0, 0));
    $("#range-month, #range-month2").val((cur_date.getMonth() + 1) + '/' + cur_date.getFullYear());

    $("#range-month").on('change', function(){
        cur_date = new Date(Date.parse($(this).datepicker('getDate')));
        start_date = new Date(cur_date.getFullYear(), cur_date.getMonth(), 1, 0, 0, 0, 0);
        end_date = new Date(cur_date.getFullYear(), cur_date.getMonth(), new Date(cur_date.getFullYear(), cur_date.getMonth()+1, 0).getDate(), 23, 59, 59, 0);
        chartAjaxLoad[0](
            start_date.getFullYear() + '-' + ((start_date.getMonth() + 1).toString().length < 2 ? '0' : '' ) + (start_date.getMonth() + 1) + '-' + (start_date.getDate().toString().length < 2 ? '0' : '' ) + start_date.getDate() + ' 00:00:00',
            end_date.getFullYear() + '-' + ((end_date.getMonth() + 1).toString().length < 2 ? '0' : '' ) + (end_date.getMonth() + 1) + '-' + (end_date.getDate().toString().length < 2 ? '0' : '' ) + end_date.getDate() + ' 23:59:59'
        );
    });

    $("#range-month2").on('change', function(){
        cur_date = new Date(Date.parse($(this).datepicker('getDate')));
        start_date = new Date(cur_date.getFullYear(), cur_date.getMonth(), 1, 0, 0, 0, 0);
        end_date = new Date(cur_date.getFullYear(), cur_date.getMonth(), new Date(cur_date.getFullYear(), cur_date.getMonth()+1, 0).getDate(), 23, 59, 59, 0);
        chartAjaxLoad[1](
            start_date.getFullYear() + '-' + ((start_date.getMonth() + 1).toString().length < 2 ? '0' : '' ) + (start_date.getMonth() + 1) + '-' + (start_date.getDate().toString().length < 2 ? '0' : '' ) + start_date.getDate() + ' 00:00:00',
            end_date.getFullYear() + '-' + ((end_date.getMonth() + 1).toString().length < 2 ? '0' : '' ) + (end_date.getMonth() + 1) + '-' + (end_date.getDate().toString().length < 2 ? '0' : '' ) + end_date.getDate() + ' 23:59:59',
            $('[name="seller_id"]').val()
        );
    });


    function initChart(id, data) {
        if (data[0].length < 1 && data.length < 2) {
            for (i in chart[id].series) {
                chart[id].series[i].setData([[]], false);
            }
        }
        data = data || null;
        if (chart[id] !== undefined && chart[id] !== null) {
            for (i in data) {
                chart[id].series[i].setData(data[i], false);
            }
            chart[id].redraw(true);
            return;
        }

        var params = {};
        var series = [];
        var xAxis = [];
        var services = JSON.parse($.ajax({
            url: "index.php/Chart/getListServices",
            async: false,
            dataType: 'json'
        }).responseText);

        switch (id) {
            case 0:
            case 1:
            case 2:
                for (i in services) {
                    series[services[i]['id']] = {
                        name: services[i]['name'],
                        data: data[i],
                        visible: false
                    }
                }

                series[0] = {
                    name: 'Продажи по всем услугам',
                    data: data[0]
                }
                for (i in data[0]) {
                    xAxis.push(data[0][i].name);
                }

                params = {
                    chart: {
                        renderTo: 'chart1',
                        type: 'column',
                        marginTop: 100,
                        marginBottom: 300,
                        height: 700,
                        options3d: {
                            enabled: true,
                            alpha: 0,
                            beta: 0,
                            depth: 50,
                            viewDistance: 25
                        }
                    },
                    title: {
                        text: 'Продажи по продавцам'
                    },
                    subtitle: {
                        text: 'графики 1, 2 и 3'
                    },
                    legend: {
                        align: 'left',
                        verticalAlign: 'bottom',
                        y: 50,
                        padding: 20,
                        itemMarginTop: 5,
                        itemMarginBottom: 5,
                        itemStyle: {
                            lineHeight: '14px'
                        }
                    },
                    plotOptions: {
                        column: {
                            depth: 25,
                            events: {
                                legendItemClick: function (event) { 
                                    //nothing todo
                                    //return false;
                                },
                                afterAnimate: function (event) {
                                    //chart[id].rangeSelector.select(0);
                                }
                           },
                       }
                    },
                    rangeSelector: {
                        buttonTheme: { // styles for the buttons
                            width: 100
                        },
                        buttons: [
                            {
                                type: 'day',
                                count: 1,
                                text: '1 день'
                            },
                            {
                                type: 'month',
                                count: 1,
                                text: '1 месяц'
                            }
                        ],
                        enabled: true,
                        allButtonsEnabled: true,
                        inputEnabled: false
                    },
                    series: series,
                    xAxis: {
                        startOnTick: false,
                        categories: xAxis,
                        events: {
                            setExtremes: function (event) {
                                $('#settings>div').each(function(index, obj) {
                                    $(obj).css('display', 'none');
                                })
                                if (event.rangeSelectorButton.type == 'month') {
                                    start_date = new Date(cur_date.getFullYear(), cur_date.getMonth(), 1, 0, 0, 0, 0);
                                    end_date = new Date(cur_date.getFullYear(), cur_date.getMonth(), new Date(cur_date.getFullYear(), cur_date.getMonth()+1, 0).getDate(), 23, 59, 59, 0);
                                    $('#settings>[name="chart2"]').css('display', '');
                                    dateRangeType = 'month';
                                }   
                                if (event.rangeSelectorButton.type == 'day') {
                                    start_date = new Date(cur_date.getFullYear(), cur_date.getMonth(), cur_date.getDate(), 0, 0, 0, 0);
                                    end_date = new Date(cur_date.getFullYear(), cur_date.getMonth(), cur_date.getDate(), 23, 59, 59, 0);
                                    $('#settings>[name="chart1"]').css('display', '');
                                    dateRangeType = 'day';
                                }
                                chartAjaxLoad[0](
                                    start_date.getFullYear() + '-' + ((start_date.getMonth() + 1).toString().length < 2 ? '0' : '' ) + (start_date.getMonth() + 1) + '-' + (start_date.getDate().toString().length < 2 ? '0' : '' ) + start_date.getDate() + ' 00:00:00',
                                    end_date.getFullYear() + '-' + ((end_date.getMonth() + 1).toString().length < 2 ? '0' : '' ) + (end_date.getMonth() + 1) + '-' + (end_date.getDate().toString().length < 2 ? '0' : '' ) + end_date.getDate() + ' 23:59:59'
                                );
                                return false;
                            }        
                       }
                    },
                    yAxis: {
                        title: {
                            text: 'Сумма (руб.)'
                        }
                    }
                }
                break;
            case 3:
                for (i in data) {
                    series[i] = {
                        name: 'Категория',
                        colorByPoint: true,
                        data: data[i]
                    }
                }
                params = {
                    chart: {
                        renderTo: 'chart4',
                        plotBackgroundColor: null,
                        plotBorderWidth: null,
                        plotShadow: false,
                        type: 'pie'
                    },
                    title: {
                        text: 'График продаж продавца в течение месяца – суммарно и по группам услуг'
                    },
                    tooltip: {
                        pointFormat: 'Процент от всех продаж: <b>{point.percentage:.1f}%</b><br>Продажи: <b>{point.y} рублей</b>'
                    },
                    plotOptions: {
                        pie: {
                            allowPointSelect: true,
                            cursor: 'pointer',
                            dataLabels: {
                                enabled: false
                            },
                            showInLegend: true,
                            point: {
                                events: {
                                    legendItemClick: function (event) {
                                        var series = chart[3].series;
                                        if (this.type == 'total' && event.target.index == 0) {
                                            series[1].hide();
                                            series[0].show();
                                        } else {
                                            series[0].hide();
                                            series[1].show();
                                            //return true;
                                        }
                                        chart[3].xAxis[0].setCategories([1, 2])
                                    },
                                    afterAnimate: function (event) {
                                        //chart[id].rangeSelector.select(0);
                                    }
                                }
                            }
                        }
                    },
                    series: series
                }
                console.log(series);
                break;

        }
        chart[id] = new Highcharts.Chart(params);
    }

    var chartAjaxLoad = [
        function(s_date, e_date){
            $.ajax({
                type: 'GET',
                url: 'index.php/Chart/get/1',
                data: {
                    'start_date': s_date,
                    'end_date': e_date
                },
                dataType: 'json',
                success: function(data){
                    var forChart = [];
                    for (i in data) {
                        forChart[i] = [];
                        if (i != 0) {
                            for (j = 0; j < forChart[0].length; j++) {
                                forChart[i][j] = {
                                    name: forChart[0][j].name,
                                    y: 0
                                }
                            }
                        }
                        for (j in data[i]) {
                            var sid = parseInt(data[i][j]['sid']) - 1;
                            forChart[i][sid] = {
                                name: data[i][j]['FIO'],
                                y: parseFloat(data[i][j]['sells'].toString().replace(/,/g, ''))
                            }
                        }
                    }
                    initChart(0, forChart);
                }
            });
        },
        function(s_date, e_date, seller_id){
            $.ajax({
                type: 'GET',
                url: 'index.php/Chart/get/2',
                data: {
                    'start_date': s_date,
                    'end_date': e_date,
                    'seller_id': seller_id
                },
                dataType: 'json',
                success: function(data){
                    var forChart = [];
                    var forChart2 = [];
                    for (i in data) {
                        if (i == 0) {
                            forChart[i] = {
                                'name': data[i].name,
                                'color': '#aa2222',
                                'type': 'total',
                                'y': parseFloat(data[i]['sells'].toString().replace(/,/g, ''))
                            }
                        } else {
                            forChart2[i - 1] = {
                                'name': data[i].name,
                                'y': parseFloat(data[i]['sells'].toString().replace(/,/g, ''))
                            }
                        }
                    }
                    initChart(3, [forChart, forChart2]);
                }
            });
        }
    ]

    $('[name="seller_id"]').on('change', function() {
        chartAjaxLoad[1](
            start_date.getFullYear() + '-' + ((start_date.getMonth() + 1).toString().length < 2 ? '0' : '' ) + (start_date.getMonth() + 1) + '-' + (start_date.getDate().toString().length < 2 ? '0' : '' ) + start_date.getDate() + ' 00:00:00',
            end_date.getFullYear() + '-' + ((end_date.getMonth() + 1).toString().length < 2 ? '0' : '' ) + (end_date.getMonth() + 1) + '-' + (end_date.getDate().toString().length < 2 ? '0' : '' ) + end_date.getDate() + ' 23:59:59',
            $(this).val()
        );
    });

    $('#button-toggle-chart').on('click', function() {
        $('#settings>div').each(function(index, obj) {
            $(obj).css('display', 'none');
        })
        if ($(this).data('frameid') == 1) {
            start_date = new Date(cur_date.getFullYear(), cur_date.getMonth(), 1, 0, 0, 0, 0);
            end_date = new Date(cur_date.getFullYear(), cur_date.getMonth(), new Date(cur_date.getFullYear(), cur_date.getMonth()+1, 0).getDate(), 23, 59, 59, 0);
            $('#chart1').css('display', 'none');
            $('#chart4').css('display', '');
            $('#settings>[name="chart4"]').css('display', '');
            $(this).data('frameid', 3);
            chartAjaxLoad[1](
                start_date.getFullYear() + '-' + ((start_date.getMonth() + 1).toString().length < 2 ? '0' : '' ) + (start_date.getMonth() + 1) + '-' + (start_date.getDate().toString().length < 2 ? '0' : '' ) + start_date.getDate() + ' 00:00:00',
                end_date.getFullYear() + '-' + ((end_date.getMonth() + 1).toString().length < 2 ? '0' : '' ) + (end_date.getMonth() + 1) + '-' + (end_date.getDate().toString().length < 2 ? '0' : '' ) + end_date.getDate() + ' 23:59:59',
                $('[name="seller_id"]').val()
            );
        } else {
            if (dateRangeType == 'month') {
                start_date = new Date(cur_date.getFullYear(), cur_date.getMonth(), 1, 0, 0, 0, 0);
                end_date = new Date(cur_date.getFullYear(), cur_date.getMonth(), new Date(cur_date.getFullYear(), cur_date.getMonth()+1, 0).getDate(), 23, 59, 59, 0);
            } else {
                start_date = new Date(cur_date.getFullYear(), cur_date.getMonth(), cur_date.getDate(), 0, 0, 0, 0);
                end_date = new Date(cur_date.getFullYear(), cur_date.getMonth(), cur_date.getDate(), 23, 59, 59, 0);
            }
            $('#chart4').css('display', 'none');
            $('#chart1').css('display', '');
            $('#settings>[name="chart1"]').css('display', '');
            $(this).data('frameid', 1);
            chartAjaxLoad[0](
                start_date.getFullYear() + '-' + ((start_date.getMonth() + 1).toString().length < 2 ? '0' : '' ) + (start_date.getMonth() + 1) + '-' + (start_date.getDate().toString().length < 2 ? '0' : '' ) + start_date.getDate() + ' 00:00:00',
                end_date.getFullYear() + '-' + ((end_date.getMonth() + 1).toString().length < 2 ? '0' : '' ) + (end_date.getMonth() + 1) + '-' + (end_date.getDate().toString().length < 2 ? '0' : '' ) + end_date.getDate() + ' 23:59:59'
            );
        }
    })

    $.ajax({
        type: 'GET',
        url: 'index.php/Chart/getSellers',
        dataType: 'json',
        success: function(data){
            var strToPrepend = '';
            for (i in data) {
                strToPrepend += '<option value="' + data[i]['id'] + '">' + data[i]['FIO'] + '</option>';
            }
            $('[name="seller_id"]').prepend(strToPrepend);
        }
    });


    chartAjaxLoad[0](
        start_date.getFullYear() + '-' + ((start_date.getMonth() + 1).toString().length < 2 ? '0' : '' ) + (start_date.getMonth() + 1) + '-' + (start_date.getDate().toString().length < 2 ? '0' : '' ) + start_date.getDate() + ' 00:00:00',
        end_date.getFullYear() + '-' + ((end_date.getMonth() + 1).toString().length < 2 ? '0' : '' ) + (end_date.getMonth() + 1) + '-' + (end_date.getDate().toString().length < 2 ? '0' : '' ) + end_date.getDate() + ' 23:59:59'
    );


    $(window).on('mousewheel', function (event) {
        return;
        if (delta = event.wheelDelta) {
            delta = event.wheelDelta / 120;
            if (window.opera) delta = -delta;
        } else {
            delta = -event.originalEvent.deltaY / 53;
        }
        chart[0].options.chart.options3d.alpha = chart[0].options.chart.options3d.alpha + delta;
        chart[0].options.chart.options3d.beta = chart[0].options.chart.options3d.beta + delta;
        console.log(chart[0].options.chart.options3d.alpha);
        chart[0].redraw(false);
    });

});