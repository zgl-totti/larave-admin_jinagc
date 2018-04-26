
<canvas id="main1" width="400" height="500"></canvas>
<canvas id="main2" width="400" height="500"></canvas>
<canvas id="main3" width="400" height="500"></canvas>
<canvas id="main4" width="400" height="500"></canvas>

<script>

    $(function () {

        var myChart1 = echarts.init(document.getElementById('main1'));
        myChart1.setOption(option = {
            title : {
                text: '{{$order_text}}',
                x:'center'
            },
            tooltip : {
                trigger: 'item',
                formatter: "{a} <br/>{b} : {c} ({d}%)"
            },
            legend: {
                orient : 'vertical',
                x : 'left',
                data:[
                    <?php foreach ($order as $v){
                    echo "'".$v['status']['status_name']."',";
                }?>
                ]
            },
            toolbox: {
                show : true,
                feature : {
                    mark : {show: true},
                    dataView : {show: true, readOnly: false},
                    magicType : {
                        show: true,
                        type: ['pie', 'funnel'],
                        option: {
                            funnel: {
                                x: '25%',
                                width: '50%',
                                funnelAlign: 'center',
                                max: 1548
                            }
                        }
                    },
                    restore : {show: true},
                    saveAsImage : {show: true}
                }
            },
            calculable : true,
            series : [
                {
                    name:'访问来源',
                    type:'pie',
                    radius : ['30%', '60%'],
                    itemStyle : {
                        normal : {
                            label : {
                                show : true
                            },
                            labelLine : {
                                show : true
                            }
                        },
                        emphasis : {
                            label : {
                                show : true,
                                position : 'center',
                                textStyle : {
                                    fontSize : '15',
                                    fontWeight : 'bold'
                                }
                            }
                        }
                    },
                    data:[
                        <?php foreach ($order as $v){
                        echo "{value:".$v['order_count'].",name:'".$v['status']['status_name']."'},";
                    }?>
                    ]
                }
            ]
        });

        var myChart2 = echarts.init(document.getElementById('main2'));
        myChart2.setOption(option = {
            title : {
                text: '{{$source_text}}',
                x:'center'
            },
            tooltip : {
                trigger: 'item',
                formatter: "{a} <br/>{b} : {c} ({d}%)"
            },
            legend: {
                orient : 'vertical',
                x : 'left',
                data:[
                    <?php foreach ($source as $v){
                    echo "'".$v['source']['source_name']."',";
                }?>
                ]
            },
            toolbox: {
                show : true,
                feature : {
                    mark : {show: true},
                    dataView : {show: true, readOnly: false},
                    magicType : {
                        show: true,
                        type: ['pie', 'funnel'],
                        option: {
                            funnel: {
                                x: '25%',
                                width: '50%',
                                funnelAlign: 'center',
                                max: 1548
                            }
                        }
                    },
                    restore : {show: true},
                    saveAsImage : {show: true}
                }
            },
            calculable : true,
            series : [
                {
                    name:'访问来源',
                    type:'pie',
                    radius : ['30%', '60%'],
                    itemStyle : {
                        normal : {
                            label : {
                                show : true
                            },
                            labelLine : {
                                show : true
                            }
                        },
                        emphasis : {
                            label : {
                                show : true,
                                position : 'center',
                                textStyle : {
                                    fontSize : '15',
                                    fontWeight : 'bold'
                                }
                            }
                        }
                    },
                    data:[
                        <?php foreach ($source as $v){
                        echo "{value:".$v['source_count'].",name:'".$v['source']['source_name']."'},";
                    }?>
                    ]
                }
            ]
        });

        var myChart3 = echarts.init(document.getElementById('main3'));
        myChart3.setOption(option = {
            title : {
                text: '{{$express_text}}',
                //subtext: '纯属虚构',
                x:'center'
            },
            tooltip : {
                trigger: 'item',
                formatter: "{a} <br/>{b} : {c} ({d}%)"
            },
            legend: {
                orient: 'vertical',
                left: 'left',

                data: [
                    <?php foreach ($express as $v){
                    echo "'".$v['express']['express_name']."',";
                }?>
                ]
            },
            series : [
                {
                    name: '访问来源',
                    type: 'pie',
                    radius : '55%',
                    center: ['50%', '60%'],

                    data:[
                        <?php foreach ($express as $v){
                        echo "{value:".$v['express_count'].",name:'".$v['express']['express_name']."'},";
                    }?>
                    ],

                    itemStyle: {
                        emphasis: {
                            shadowBlur: 10,
                            shadowOffsetX: 0,
                            shadowColor: 'rgba(0, 0, 0, 0.5)'
                        }
                    }
                }
            ]
        });

        var myChart4 = echarts.init(document.getElementById('main4'));
        myChart4.setOption(option = {
            title : {
                text: '{{$pay_text}}',
                //subtext: '纯属虚构',
                x:'center'
            },
            tooltip : {
                trigger: 'item',
                formatter: "{a} <br/>{b} : {c} ({d}%)"
            },
            legend: {
                orient: 'vertical',
                left: 'left',

                data: [
                    <?php foreach ($pay as $v){
                    echo "'".$v['pay']['pay_name']."',";
                }?>
                ]
            },
            series : [
                {
                    name: '访问来源',
                    type: 'pie',
                    radius : '55%',
                    center: ['50%', '60%'],

                    data:[
                        <?php foreach ($pay as $v){
                        echo "{value:".$v['pay_count'].",name:'".$v['pay']['pay_name']."'},";
                    }?>
                    ],

                    itemStyle: {
                        emphasis: {
                            shadowBlur: 10,
                            shadowOffsetX: 0,
                            shadowColor: 'rgba(0, 0, 0, 0.5)'
                        }
                    }
                }
            ]
        });
    });
</script>