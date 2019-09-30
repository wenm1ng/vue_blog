<template>
  <div>
    <div class="form-box">
      <img class="banner-img" src="/res/img/liuyan.jpg">
      <div class="form">
        <form class="layui-form" action="">
          <div class="layui-form-item layui-form-text">
            <div class="layui-input-block">
              <textarea name="desc" placeholder="既然来了，就说几句" class="layui-textarea" v-model="content"></textarea>
            </div>
          </div>
          <div class="layui-form-item">
            <div class="layui-input-block" style="text-align: right;">
              <a class="layui-btn definite" @click="post_comment()">確定</a>
            </div>
          </div>
        </form>
      </div>
    </div>
    <div class="volume">
      全部留言 <span>{{comment_count}}</span>
    </div>
    <div class="list-cont" v-for="v,k in list">        
      <div class="cont" :style="border_bottom(v.user_id)">
        <div class="img">
          <img src="/res/img/user-default.jpg" alt="" style="width:50px;height:50px">
        </div>
        <div class="text">
          <p class="tit"><span class="name" v-if="v.user_id != 1">{{v.user_name}}</span><span class="name" v-else>{{v.user_name}}&nbsp;&nbsp;回复&nbsp;&nbsp;{{v.to_user_name}}</span><span class="data">{{v.create_time}}</span></p>
          <p class="ct">{{v.content}}</p>
        </div>
      </div>
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
                                    if($.inArray(self.temp_list[i]['id'],self.message_id_list) <= -1){
                                        self.message_id_list.push(self.temp_list[i]['id']);
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
                message_id_list:[],
                comment_count:0
            }
        },
        methods:{
            index:function(){
                let that = this;
                let url = '/message/list';
                axios.post(url,{page:this.page},{emulateJSON:true}).then(function(res){
                    that.list = res.data.data;
                    that.comment_count = res.data.count;
                    for (var i = 0; i < res.data.data.length; i++) {
                        that.message_id_list.push(res.data.data[i]['id']);
                    }
                });
            },
            append:function(){
                let that = this;
                let url = '/message/list';
                axios.post(url,{page:this.page},{emulateJSON:true}).then(function(res){
                    that.temp_list = res.data.data;
                });
            },
            post_comment:function(){
              let that = this;
              let url = "/message/comment";
              if(this.content == ''){
                return;
              }
              axios.post(url,{content:this.content},{emulateJSON:true}).then(function(res){
                if(res.data.status == 1){
                  that.list.unshift(res.data.data);
                  that.comment_count++;
                  that.content = '';
                }
              });
            },
            border_bottom:function(user_id){
              if(user_id == 1){
                return 'border-bottom:1px solid #e5e5e5';
              }
            }
        }
    };
</script>