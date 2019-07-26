<template>
	<div>
		<div class="content whisper-content leacots-content details-content">
		    <div class="cont w1000" v-if="list">
		      <div class="whisper-list">
		        <div class="item-box">
		          <div class="review-version">
		              <div class="volume">
		                全部留言 <span>{{ count }}</span>
		              </div>
		              <div class="list-cont" style="border-bottom: 1px solid #e5e5e5;" v-for="val in list_fun">
		                
		                <div class="cont" style="border-bottom: none">
		                  <div class="img">
                            <img src="/res/img/header.png" alt="" v-if="val.user_id == 1">
		                    <img src="/res/img/user-default.jpg" alt="" v-if="val.user_id != 1" style="width:50px;height:50px">
                          </div>
		                  <div class="text">
		                    <p class="tit"><span class="name">{{ val.user_name }}</span><span class="data">{{ val.create_time }}</span></p>
		                    <p class="ct">{{ val.content }}</p>
                            <article_child :article_id="val.article_id" :to_user_id="val.to_user_id" :to_user_name="val.user_name" :link_comment_id="val.comment_id" @post_comment="post_comment"></article_child>
		                  </div>
		                </div>
		                <div class="cont" style="border-bottom: none" v-for="child in val.child">
                          <div class="img">
                            <img src="/res/img/header.png" alt="" v-if="child.user_id == 1">
                            <img src="/res/img/user-default.jpg" alt="" v-if="child.user_id != 1" style="width:50px;height:50px">
                          </div>
                          <div class="text">
                            <p class="tit"><span class="name">{{ child.user_name }}&nbsp;&nbsp;回复&nbsp;&nbsp;{{ child.to_user_name }}</span><span class="data">{{ child.create_time }}</span></p>
                            <p class="ct">{{ child.content }}</p>
                          </div>
                            <article_child :article_id="child.article_id" :to_user_id="child.to_user_id" :to_user_name="child.user_name" :link_comment_id="val.comment_id" @post_comment="post_comment"></article_child>
                        </div>
		              </div>
		          </div>
		        </div>
		      </div>
		      <div id="demo" style="text-align: center;"></div>
		    </div>
    		<foot></foot>

		  </div>
		  
	</div>
</template>

<script >
    import $ from 'jquery';
    import bus from '../../eventBus';
    export default {
        // props:['list','count'],
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
            var val = self.list;
            bus.$on('comment_fun',(res)=>{
                // if(val.length == 0){
                //     console.log(111);
                //    var arr = [];
                //    arr.push(res);
                //     Vue.set(arr,res['comment_id'],res);
                //    self.list = arr;
                //    console.log(self.list);
                // }else{
                //     // console.log(222);
                //     Vue.set(self.list,res['comment_id'],res);
                // }
                Vue.set(self.list,'c_'+res['comment_id'],res);
                self.count++;
                // that.splice(parseInt(res['comment_id']),0,res);
            })
            // var is_scroll = true;
            // $(window).scroll(function(){
            //     let scrollTop = $(this).scrollTop();
            //     // console.log(scrollTop);
            //     let scrollHeight = $(document).height();
            //     // console.log(scrollHeight);
            //     let windowHeight = $(this).height();

            //     // if(scrollTop + windowHeight === scrollHeight){
            //     if(scrollHeight - scrollTop <= 1479){
            //         if(is_scroll){
            //             // console.log(is_scroll);
            //             is_scroll = false;
            //             self.page++;
            //             self.append();
            //             if(self.temp_list){
            //                 self.is_show = true;
            //                 self.font_show = false;

            //                 setTimeout(function(){
            //                     self.is_show = false;
            //                    for (var i = 0; i < self.temp_list.length; i++) {
            //                         if($.inArray(self.temp_list[i]['article_id'],self.article_list) <= -1){
            //                             self.article_list.push(self.temp_list[i]['article_id']);
            //                             self.list.push(self.temp_list[i]);
            //                         }
            //                     } 
            //                 },500)
            //                 if(self.temp_list.length < 10 && self.list.length > 10){
            //                     self.font_show = true;
            //                 }
            //             }                   
            //         }
            //     }else{
            //         is_scroll = true;
            //     }
            // })
        },
        computed:{
            list_fun:function(){
                var list = [];
                for(var i in this.list){
                    list.unshift(this.list[i]);
                }
                console.log(list);
                return list;
            }
        },
        data(){
            return {
                id:$("#article_id").val(),
                list:[],
                count:0,
                temp_list:[],
                is_show:false,
                font_show:false,
                article_list:[],
            }
        },
        methods:{
            index:function(){
                let that = this;
                let url = '/article/index';
                axios.post(url,{id:this.id},{emulateJSON:true}).then(function(res){
                    that.list = res.data.data;
                    that.count = res.data.count;
                    // for (var i = 0; i < res.data.data.length; i++) {
                    //     that.article_list.push(res.data.data[i]['article_id']);
                    // }
                });
            },
            show_reply:function(){
                this.is_show = !this.is_show;
            },
            post_comment:function(res){
                this.list['c_'+res['link_comment_id']]['child'].push(res);
                this.count++;
            }
            
        }
    };
</script>