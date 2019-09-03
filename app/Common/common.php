<?php

/**
 * 自动加载类
*/
function loader($class) {

    $include_path = array(API_DIR.DS."lib");
    foreach($include_path as $path){
        $filename = $path.DS."{$class}.php";
        if(file_exists($filename)){
            include $filename;
            return true;
        }
    }

    $autoloadConfig = array(API_DIR);

    $lowerName = strtolower($class);
    $explodeName = explode('_', $lowerName);
    $tname = $explodeName[0] . DS . str_replace('_', '.', $lowerName);

    foreach ($autoloadConfig as $path) {
        $filename = $path . DS . "{$tname}.php";
        if (file_exists($filename)) {
            include ($filename);
            return true;
        }
    }

    return false;
}

/**
 * 根据环境不同加载不同的配置文件
*/
function loadConfigFile($env){
    include_once(path_format('config/'.$env.'/config.php'));
}

/**
 * 获取头部信息
**/
// function getallheaders(){
//     foreach ($_SERVER as $name => $value){
//         if (substr($name, 0, 5) == 'HTTP_'){
//             $headers[str_replace(' ', '-', ucwords(strtolower(str_replace('_', ' ', substr($name, 5)))))] = $value;
//         }
//     }
//     return $headers;
// }

/**
 * 得到毫秒时间戳
*/
function getMillisecond() {
    list($t1, $t2) = explode(' ', microtime());
    return (float)sprintf('%.0f', (floatval($t1) + floatval($t2)) * 1000);
}

/**
 * 得到访问的ip
*/
function get_client_ip() {
    static $ip = NULL;
    if ($ip !== NULL) return $ip;
    if (isset($_SERVER['HTTP_X_FORWARDED_FOR'])) {
        $arr = explode(',', $_SERVER['HTTP_X_FORWARDED_FOR']);
        $pos =  array_search('unknown',$arr);
        if(false !== $pos) unset($arr[$pos]);
        $ip   =  trim($arr[0]);
    }elseif (isset($_SERVER['HTTP_CLIENT_IP'])) {
        $ip = $_SERVER['HTTP_CLIENT_IP'];
    }elseif (isset($_SERVER['REMOTE_ADDR'])) {
        $ip = $_SERVER['REMOTE_ADDR'];
    }
    // IP地址合法验证
    $ip = (false !== ip2long($ip)) ? $ip : '0.0.0.0';
    return $ip;
}


/**
 * 13位异或加密
*/
function comm_xor_data($string) {

    $len = strlen($string);
    for($i=0;$i<$len;$i++){

        $string[$i] = chr(ord($string[$i])^13);
    }
    return $string;
}

/**
 * post请求
*/
function comm_curl_post($url, $postData, $timeout=15){
    $ch = curl_init();//初始化curl
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_HEADER, 0);//设置header
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);//要求结果为字符串且输出到屏幕上
    curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $timeout); // 建立连接时候的超时设置
    curl_setopt($ch, CURLOPT_TIMEOUT, $timeout);      // 接收信息时的超时设置
    curl_setopt($ch, CURLOPT_POST, 1);//post提交方式
    curl_setopt($ch, CURLOPT_POSTFIELDS, $postData);
    curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 1);
    $data = curl_exec($ch);//运行curl
    curl_close($ch);
    return $data;
}

/**
 * get请求
*/
function comm_curl_get($url, $timeout=1){
    $ch = curl_init();//初始化curl
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);//要求结果为字符串且输出到屏幕上
    curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
    $data = curl_exec($ch);//运行curl
    curl_close($ch);
    return $data;
}

/**
 * 去掉表情符号
*/
function comm_filter_emoji($str){

    $str = preg_replace_callback('/./u',function (array $match) {
        return strlen($match[0]) >= 4 ? '' : $match[0];
    },
    $str);

    return $str;
}

/**
 * 字符串加*
*/
function get_new_str($str){

    $count = mb_strlen($str, 'utf8');
    $new_str = '*';
    for ($i=0; $i < $count; $i++) {
        $new_str .= mb_substr($str, 0+$i, 1,'utf8') .'*';
    }

    return $new_str;
}

/**
 * curl发送get请求、post请求、input请求(content_type: application/json)
*/
function get_request($url = '',$data = array(),$add_args = array(),$header_more = array()){
    
    //设置函数的默认参数
    $add_args_default = array(
        "content_type" => "application/x-www-form-urlencoded",
        "timeout" => 15
    );
    
    $add_args = array_merge($add_args_default,$add_args);
    extract($add_args);

    //设置curl参数
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_HEADER, false);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_TIMEOUT, $timeout);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);

    // get请求转换为post请求或INPUT请求
    if (!empty($data)){
        if($content_type == 'application/json'){ //INPUT请求
            $data_str = json_encode($data);
        }else{ //post请求
            $data_str = http_build_query($data);
        }
        $header = array(
            "Content-type:" . $content_type,
            "Content-Length:" . strlen($data_str)
        );
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data_str);
    }

    if(!empty($header_more)){
        if (empty($header)) {
            $header = array();
        }
        $header = array_merge($header,$header_more);
    }

    if (!empty($header)) {
        curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
    }

    $output = curl_exec($ch);
    $curl_errno = curl_errno($ch);
    curl_close($ch);

    return $output;
}

/**
 * 发送curl请求,可同时(异步)处理多个请求
 * $url_arr=array(array("url"=>"","data"=>""));
 * $url_arr=array("k1"=>array("url"=>"","data"=>""));
*/
function curl_mrequest($url_arr = array(), $add_args = array(),$header = array()){

    $add_args_default = array(
        'timeout' => 30,
        'method' => 'get'
    );
    $add_args = array_merge($add_args_default,$add_args);

    $mh = curl_multi_init(); #初始化一个curl_multi句柄

    // 增加多个句柄
    $ch_arr = array();
    foreach($url_arr as $key => $param){

        $ch_arr[$key] = curl_init();
        $url = $param["url"];
        $data = $param["data"];
        // 根据method参数判断是post还是get方式提交数据
        if(strtolower($add_args['method']) === "get"){
            if($data){
                $url = "$url?" . http_build_query( $data ); 
            }
        }else{
            curl_setopt($ch_arr[$key],CURLOPT_POSTFIELDS,$data);
        }

        curl_setopt_array($ch_arr[$key], array(
            CURLOPT_URL => $url,
            CURLOPT_HEADER => false,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_TIMEOUT => $add_args['timeout'],
            CURLOPT_SSL_VERIFYPEER => false,
            CURLOPT_SSL_VERIFYHOST => false,
        ));

        if(!empty($header)){
            curl_setopt($ch_arr[$key], CURLOPT_HTTPHEADER, $header);
        }

        curl_multi_add_handle($mh, $ch_arr[$key]);
    }

    //处理多个请求
    $running = null;
    $result = array(); #curl数组用来记录各个curl句柄的返回值
    do {
        curl_multi_exec($mh,$running);
    }while($running > 0 );

    //获取请求后的数据
    foreach ($ch_arr as $key => $ch) {
        $result[$key] = curl_multi_getcontent($ch);
        curl_multi_remove_handle($mh,$ch);
    }

    curl_multi_close($mh); #关闭curl_multi句柄

    return $result;
}

function obj_to_array($list){
    return json_decode(json_encode($list),true);
}

/**
 * 数组转xml
*/
function comm_arrtoxml($data, $rootxml) {
    $xmlObj = new SimpleXMLElement($rootxml);
    $arr = array_flip($data);
    array_walk_recursive($arr, array($xmlObj, 'addChild'));
    return $xmlObj->asXML();
}

/**
 * xml转数组
*/
function comm_xmltoarr($xml) {
    return json_decode(json_encode(simplexml_load_string($xml, 'SimpleXMLElement', LIBXML_NOCDATA)), true);
}

// 统一正确json格式返回
function comm_return_data($data = [], $count = 0, $code = 0, $msg = 'ok') {

    $response = array();
    $response['code'] = $code;
    $response['msg'] = $msg;
    $response['count'] = $count;
    $response['data'] = $data !== null ? $data : [];
    $response = json_encode($response);

    echo $response;
    return;
}

// 统一错误json格式返回
function comm_return_error($desc,$code = -1) {
    comm_return_data(null, 0, $code, $desc);
    exit(0);
}

// 获取随机字符串
function comm_rand_str($len) {

    $rand_str = "qwertyupasdfghjkzxcvbnm123456789";
    $str = '';
    for ($i=0; $i < $len; $i++) {
        $rand = rand(0,strlen($rand_str)-1);
        $str .= $rand_str[$rand];
    }
    return $str;
}

// 获取随机数字串
function comm_rand_num_str($len) {

    $rand_str = '0123456789';
    $str = '';
    for($i = 0 ; $i < $len ; $i++) {
        $rand = rand(0,strlen($rand_str)-1);
        $str .= $rand_str[$rand];
    }
    return $str;
}

// 截取中文，多余的补‘...’
function comm_substr($str,$len){
    return mb_substr($str,0,$len,'utf8').(mb_strlen($str,'utf8')>$len?'...':'');
}

// 敏感字过滤
function keywords_filter($keywords_arr,$str){
    $badword1 = array_combine($keywords_arr,array_fill(0,count($keywords_arr),'*'));
    return strtr($str, $badword1);
}

//参数排序输出
function getSign($params){
    ksort($params);//将参数按key进行排序
    $str = '';
    foreach ($params as $k => $val) {
        $str .= "{$k}={$val}&"; //拼接成 key1=value1&key2=value2&...&keyN=valueN& 的形式
    }
    return rtrim($str, "&");
}

//循环处理参数
function handle_input_data($arr){
    $new_arr = array();
    foreach ($arr as $key => $val) {
        $new_arr[$key] = htmlspecialchars(trim($val));
    }
    return $new_arr;
}