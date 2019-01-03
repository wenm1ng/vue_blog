<template>
    <div v-if="list">
      <div class="banner">
        <div class="cont w1000">
          <div class="title">
            <h3>小明<br />博客</h3>
            <h4>一位向着架构师进发的php攻城狮。。</h4>
          </div>
          <!-- <div class="amount">
            <p><span class="text">访问量</span><span class="access">1000</span></p>
            <p><span class="text">日志</span><span class="daily-record">1000</span></p>
          </div> -->
        </div>
      </div>
      <!-- <div class="tlinks">Collect from <a href="http://www.cssmoban.com/" >网页模板</a></div> -->
      <div class="content">
        <div class="cont w1000">
          <div class="title">
            <span class="layui-breadcrumb" lay-separator="|" style="display:block">
              <a href="javascript:;" class="active">设计文章</a>
              <a href="javascript:;">前端文章</a>
              <a href="javascript:;">旅游杂记</a>
            </span>
          </div>
          <div class="list-item">

            <div class="item" v-for="val in list">
              <div class="layui-fluid">
                <div class="layui-row">
                  <div class="layui-col-xs12 layui-col-sm4 layui-col-md5">
                    <div class="img"><a :href="'/article/'+val.article_id"><img :src="val.img" alt=""></a></div>
                  </div>
                  <div class="layui-col-xs12 layui-col-sm8 layui-col-md7">
                    <div class="item-cont">
                      <h3><a :href="'/article/'+val.article_id">{{ val.title }}</a><button class="layui-btn layui-btn-danger new-icon">new</button></h3>
                      <h5>{{ val.key }}</h5>
                      <p>{{ val.content }}</p>
                      <a :href="'/article/'+val.article_id" class="go-icon"></a>
                    </div>
                </div>
                </div>
               </div>
            </div>
            
          <div id="demo" style="text-align: center;"></div>
        </div>
      </div>
      <!--loading-->
      <img src="../../img/loading2.gif" alt="" style="margin-left:40%;" v-show="is_show">
      <div style="margin-left:50%;margin-top:15px;" v-show="font_show">
        <p style="line-height:30px;font-size:15px;" >没有更多内容了噢~~</p>
      </div>
    <foot></foot>
    </div>
</div>
</template>

<script >
    import $ from 'jquery'
    export default {
        // components:{
        //   Footer,
        // },
        created(){
            $("#menu").click(function(){
                if($("#title").css('display') == 'block'){
                    $("#title").toggle(false);
                }else{
                    $("#title").toggle(true);
                }
            })
            this.index();
        },
        mounted() {
            var self = this;
            var is_scroll = true;
            $(window).scroll(function(){
                let scrollTop = $(this).scrollTop();
                // console.log(scrollTop);
                let scrollHeight = $(document).height();
                // console.log(scrollHeight);
                let windowHeight = $(this).height();

                // if(scrollTop + windowHeight === scrollHeight){
                if(scrollHeight - scrollTop <= 1479){
                    if(is_scroll){
                        // console.log(is_scroll);
                        is_scroll = false;
                        self.page++;
                        self.append();
                        if(self.temp_list){
                            self.is_show = true;
                            self.font_show = false;

                            setTimeout(function(){
                                self.is_show = false;
                               for (var i = 0; i < self.temp_list.length; i++) {
                                    if($.inArray(self.temp_list[i]['article_id'],self.article_list) <= -1){
                                        self.article_list.push(self.temp_list[i]['article_id']);
                                        self.list.push(self.temp_list[i]);
                                    }
                                } 
                            },500)
                            if(self.temp_list.length < 10 && self.list.length > 10){
                                self.font_show = true;
                            }
                        }                   
                    }
                }else{
                    is_scroll = true;
                }
            })
        },
        data(){
            return {
                page:1,
                list:[],
                temp_list:[],
                is_show:false,
                font_show:false,
                article_list:[],
            }
        },
        methods:{
            index:function(){
                let that = this;
                let url = '/index';
                axios.post(url,{page:this.page},{emulateJSON:true}).then(function(res){
                    that.list = res.data.data;
                    for (var i = 0; i < res.data.data.length; i++) {
                        that.article_list.push(res.data.data[i]['article_id']);
                    }
                });
            },
            append:function(){
                let that = this;
                let url = '/index';
                axios.post(url,{page:this.page},{emulateJSON:true}).then(function(res){
                    that.temp_list = res.data.data;
                });
            }
            // created:function(){
            //     clearTimeout(this.timer);
            //     this.timer=setTimeout(()=>{
            //         var clientHeight=document.documentElement.clientHeight; //document.documentElement获取数据
            //         var scrollTop=document.documentElement.scrollTop; //document.documentElement获取数据
            //         var scrollHeight=document.documentElement.scrollHeight;//document.documentElement获取数据
            //         if(clientHeight+scrollTop+20>=scrollHeight){
            //             this.pageIndex++;
            //             this.getBooks();
            //         }
            //     },13);
            // }
            
        }
    };
</script>


