<template>
  <div>
    <div class="list-cont">
      <div class="cont">
        <div class="img">
          <img src="" alt="">
        </div>
        <div class="text">
          <p class="tit"><span class="name">吳亦凡</span><span class="data">2018/06/06</span></p>
          <p class="ct">敢问大师，师从何方？上古高人呐逐一地看完你的作品后，我的心久久 不能平静！这世间怎么可能还有如此精辟的作品？我不敢相信自己的眼睛。自从改革开放以后，我就以为再也不会有任何作品能打动我，没想到今天看到这个如此精妙绝伦的作品好厉害！</p>
        </div>
      </div>
    </div>
  </div>
</template>

<script >
    import $ from 'jquery'
    export default {
      props:['whispers_id'],
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