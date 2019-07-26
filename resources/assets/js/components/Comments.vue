<template>
    <div class="form">
	  <div class="layui-form-item layui-form-text">
	    <div class="layui-input-block">
	      <textarea name="desc" v-model="content" placeholder="既然来了，就说几句" class="layui-textarea"></textarea>
	    </div>
	  </div>
	  <div class="layui-form-item">
	    <div class="layui-input-block" style="text-align: right;">
	      <button class="layui-btn definite" @click="post_comment">確定</button>
	    </div>
	  </div>
	</div>
</template>
<script >
    import $ from 'jquery';
    import bus from '../../eventBus';
    export default {
        created(){
            
        },
        mounted() {
            
        },
        data(){
            return {
            	article_id:$("#article_id").val(),
            	link_comment_id:0,
            	to_user_id:1,
            	to_user_name:'文明',
            	is_reply:0,
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
            	if(this.content != ''){
            		let that = this;
	            	let url = "/article/reply";
	            	axios.post(url,{article_id:this.article_id,link_comment_id:this.link_comment_id,content:this.content,to_user_id:this.to_user_id,to_user_name:this.to_user_name,is_reply:this.is_reply},{emulateJSON:true}).then(function(res){
	            		if(res.data.status == 1){
	                    	that.content = '';
	                    	bus.$emit('comment_fun',res.data.data);
	            		}
	                });
            	}
            }
        }
    };
</script>