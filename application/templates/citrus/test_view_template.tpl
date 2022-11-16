<div style="text-align: center">
    
    <h1><?php echo $param1; ?></h1>
    
    <?php foreach($arr1 as $i => $v){ ?>
        <p><?php echo $v; ?></p>
    <?php } ?>
        
    <p><button id="ajax-button">Test ajax</button></p>
       
    <div class="ajax-container"></div>
</div>
<script>
    //check ajax
    $(function(){
        
        $(document).on('click','#ajax-button',function(){
            
            Ajax.Post('/citrus/ajaxCheck',{href:location.href},function(response){
                console.log(response);
                if (response.status == 0){
                    $('.ajax-container').html(response.result.html);
                }else{
                    alert('ajax error!');
                }
            });
            
        });
        
    });
</script>