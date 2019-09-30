<template>
  <div class="item-box" v-if="list.length">
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
          <p class="edit"><i class="layui-icon layui-icon-reply-fill"></i><span  v-on:click="open(val.id)">{{val.comment_count}}</span></p>
          <p class="off" v-if="!comment_show || comment_show != val.id" v-on:click="open(val.id)"><span>展开</span><i class="layui-icon layui-icon-down"></i></p>
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
          <!-- <div class="list-cont" v-for="v,k in val.child">
            <div class="cont" style="border-bottom: 1px solid #e5e5e5;">
              <div class="img">
                <img src="/res/img/user-default.jpg" alt="" style="width:50px;height:50px" v-if="v.user_id != 1" >
                <img src="/res/img/header.png" alt="" style="width:50px;height:50px" v-else >
              </div>
              <div class="text">
                <p class="tit"><span class="name">{{ val.user_name }}</span><span class="data">{{ val.create_time }}</span></p>
                <p class="ct">{{ val.content }}</p>
              </div>
            </div>
            <div class="cont" style="border-bottom: none">
              <div class="img">
                <img src="/res/img/user-default.jpg" alt="" style="width:50px;height:50px" v-if="v.user_id != 1" >
                <img src="/res/img/header.png" alt="" style="width:50px;height:50px" v-else >
              </div>
              <div class="text">
                <p class="tit"><span class="name" v-if="v.user_id != 1">{{v.user_name}}</span><span class="name" v-else>{{v.user_name}}&nbsp;&nbsp;回复&nbsp;&nbsp;{{v.to_user_name}}</span><span class="data">{{v.create_time}}</span></p>
                <p class="ct">{{v.content}}</p>
              </div>
            </div>
          </div> -->
          <div v-if="comment_list[val.id]">
            <div class="list-cont" style="border-bottom: 1px solid #e5e5e5;" v-for="v in comment_list[val.id]">
                    
              <div class="cont" style="border-bottom: none">
                <div class="img">
                      <img src="/res/img/header.png" alt="" v-if="v.user_id == 1">
                  <img src="/res/img/user-default.jpg" alt="" v-if="v.user_id != 1" style="width:50px;height:50px">
                    </div>
                <div class="text">
                  <p class="tit"><span class="name">{{ v.user_name }}</span><span class="data">{{ v.create_time }}</span></p>
                  <p class="ct">{{ v.content }}</p>
                </div>
              </div>
              <div class="cont" style="border-bottom: none" v-for="child in v.child">
                    <div class="img">
                      <img src="/res/img/header.png" alt="" v-if="child.user_id == 1">
                      <img src="/res/img/user-default.jpg" alt="" v-if="child.user_id != 1" style="width:50px;height:50px">
                    </div>
                    <div class="text">
                      <p class="tit"><span class="name">{{ child.user_name }}&nbsp;&nbsp;回复&nbsp;&nbsp;{{ child.to_user_name }}</span><span class="data">{{ child.create_time }}</span></p>
                      <p class="ct">{{ child.content }}</p>
                    </div>
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
            var that = this;
            setTimeout(function(){
              $.each(that.list,function(index, el) {
                that.comment_list[el.id] = [];
                that.comment_time_list[el.id] = 0;
              });
              console.log(that.comment_list);
              console.log(that.comment_time_list);
            },2000)
            
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
                if(scrollHeight - scrollTop <= 1479 + self.comment_height){
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
                comment_height:0,
                comment_list:[],
                comment_time_list:[],
                comment_page:1,
                content:''
            }
        },
        methods:{
            index:function(){
                let that = this;
                let url = '/whisper/list';
                axios.post(url,{page:this.page},{emulateJSON:true}).then(function(res){
                    that.list = res.data.data;
                    that.comment_list.push(res.data.comment_list);
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
            open:function(id){
              // console.log(this.comment_list);
              // console.log(id);
              this.comment_show = id;
              if(id > 0 && this.comment_time_list[id] < 1){
                let that = this;
                let url = '/whisper/comment_list';
                $.ajax({
                  url:url,
                  type:'POST',
                  async:false,
                  data:{page:that.comment_page,id:id},
                  dataType:'json',
                  success:function(res){
                    that.comment_list[id] = res.data;
                    that.comment_time_list[id]++;
                  }
                });
                // axios.post(url,{page:this.comment_page,id:id},{emulateJSON:true}).then(function(res){
                //     that.comment_list[id] = res.data.data;
                //     that.comment_time_list[id]++;
                // });
              }
              // var page = this.page;
              // var height = this.comment_list[page-1][(index-1)+(page-1)*10].length;
              // if(index == 0){
              //   this.comment_height -= height;
              // }else{
              //   this.comment_height += height;
              // }
              
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
            },
            border_bottom:function(user_id){
              if(user_id == 1){
                return 'border-bottom:1px solid #e5e5e5';
              }
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