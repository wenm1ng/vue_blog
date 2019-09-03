<template>
  <div class="item-box" v-if="list">
    <div v-for="val,index in list">
      <div class="item">
        <div class="whisper-title">
          <i class="layui-icon layui-icon-date"></i><span class="hour">{{val.time}}</span><span class="date">{{val.date}}</span>
        </div>
        <p class="text-cont">
          {{val.content}}
        </p>
        <div class="img-box">
          <img :src="val.img" style="width:260px">
        </div>
        <div class="op-list">
          <!-- <p class="like"><i class="layui-icon layui-icon-praise"></i><span>1200</span></p>  -->
          <p class="edit"><i class="layui-icon layui-icon-reply-fill"></i><span  v-on:click="open(index+1)">{{val.comment_count}}</span></p>
          <p class="off" v-if="!comment_show || comment_show != index+1" v-on:click="open(index+1)"><span>展开</span><i class="layui-icon layui-icon-down"></i></p>
          <p class="off" v-else v-on:click="open(0)"><span>收起</span><i class="layui-icon layui-icon-up"></i></p>
        </div>
      </div>
      <transition name="fade">
        <div class="review-version" v-show="comment_show === index+1">
          <div class="form">
            <img src="">
            <form class="layui-form" action="">
              <div class="layui-form-item layui-form-text">
                <div class="layui-input-block">
                  <textarea name="desc" class="layui-textarea" v-model="content"></textarea>
                </div>
              </div>
              <div class="layui-form-item">
                <div class="layui-input-block" style="text-align: right;">
                  <a class="layui-btn definite" @click="post_comment(val.id,index)">確定</a>
                </div>
              </div>
            </form>
          </div>
          <div class="list-cont" v-for="v,k in val.comment">
            <div class="cont" style="border-bottom: 1px solid #e5e5e5;">
              <div class="img">
                <img src="/res/img/user-default.jpg" alt="" style="width:50px;height:50px">
              </div>
              <div class="text">
                <p class="tit"><span class="name">{{v.user_name}}</span><span class="data">{{v.create_time}}</span></p>
                <p class="ct">{{v.content}}</p>
              </div>
            </div>
          </div>
        </div>
      </transition>
    </div>
    
    <img src="../../img/loading2.gif" alt="" style="margin-left:40%;" v-show="is_show">
      <div style="margin-left:50%;margin-top:15px;" v-show="font_show">
        <p style="line-height:30px;font-size:15px;" >没有更多内容了噢~~</p>
      </div>
    <foot></foot>
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
                    if(is_scroll && self.font_show == false){
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
                                    if($.inArray(self.temp_list[i]['id'],self.whisper_id_list) <= -1){
                                        self.whisper_id_list.push(self.temp_list[i]['id']);
                                        self.list.push(self.temp_list[i]);
                                    }
                                } 
                            },500)
                            if((self.temp_list.length < 10 && self.list.length > 10) || self.list.length < 10){
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
                comment_show:false,
                whisper_id_list:[],
            }
        },
        methods:{
            index:function(){
                let that = this;
                let url = '/whisper/list';
                axios.post(url,{page:this.page},{emulateJSON:true}).then(function(res){
                    that.list = res.data.data;
                    for (var i = 0; i < res.data.data.length; i++) {
                        that.whisper_id_list.push(res.data.data[i]['id']);
                    }
                });
            },
            append:function(){
                let that = this;
                let url = '/whisper/list';
                axios.post(url,{page:this.page},{emulateJSON:true}).then(function(res){
                    that.temp_list = res.data.data;
                });
            },
            open:function(index){
              this.comment_show = index;
            },
            post_comment:function(id,index){
              let that = this;
              let url = "/whisper/comment";
              if(this.content == ''){
                return;
              }
              axios.post(url,{id:id,content:this.content},{emulateJSON:true}).then(function(res){
                if(res.data.status == 1){
                  that.list[index]['comment'].unshift(res.data.data);
                  that.list[index]['comment_count']++;
                  that.content = '';
                }
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

<!-- <script type="text/javascript" src="../../res/layui/layui.js"></script>
<script type="text/javascript">
   layui.config({
      base: '../../res/js/util/'
    }).use(['element','form','menu'],function(){
      element = layui.element,form = layui.form,menu = layui.menu;
      
      menu.init();
      menu.off();
      menu.submit();
    });
</script>
 -->