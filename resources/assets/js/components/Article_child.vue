<template>
	<div>
		<p style="float:right;"><a href="javascript:void(0)" style="color:#ff7f21" @click="show_reply">回复</a></p>
	    <div class="form" v-show="is_show" style="margin:0;margin-top:30px">
		    <!-- <form @submit.prevent="post_comment" accept-charset="UTF-8"> -->
		      <div class="layui-form-item layui-form-text">
		        <div class="layui-input-block">
		          <textarea name="desc" v-model="content" placeholder="请尽情用口水轰炸我" class="layui-textarea"></textarea>
		        </div>
		      </div>
		      <div class="layui-form-item">
		        <div class="layui-input-block" style="text-align: right;">
		          <button class="layui-btn definite" @click="post_comment">確定</button>
		        </div>
		      </div>
		    <!-- </form> -->
	    </div>
	</div>
</template>
<script >
    import $ from 'jquery'
    export default {
    	props:['article_id','to_user_id','to_user_name','link_comment_id'],
        created(){
            
        },
        mounted() {
            
        },
        data(){
            return {
            	is_reply:1,
            	content:'',
                list:[],
                is_show:false,
            }
        },
        methods:{
            show_reply:function(){
                this.is_show = !this.is_show;
            },
            post_comment:function(){
            	let that = this;
            	let url = "/article/reply";
            	axios.post(url,{article_id:this.article_id,link_comment_id:this.link_comment_id,content:this.content,to_user_id:this.to_user_id,to_user_name:this.to_user_name,is_reply:this.is_reply},{emulateJSON:true}).then(function(res){
            		if(res.data.status == 1){
                    	that.is_show = !that.is_show;
                    	that.content = '';
                    	that.$emit('post_comment',res.data.data);
            		}
                });

             	
            }
        }
    };
</script>