
function GetCarList(model_id)
    {
    $(".loading").ajaxStart(function(){ $(this).show(); });
    $(".loading").ajaxComplete(function(){ $(this).hide(); });

    $.ajax({
           type: "POST",
           url: "init_ajax.php?script=cars.get_car_list",
           data: "model_id="+model_id,
           success: function(data){

             var cars = jQuery.parseJSON(data);
             var list = "";
             for (var car_id in cars) {
                    list += '<li id="'+car_id+'"><input type="checkbox"  name="arr_car_dell[]" value="'+car_id+'">&nbsp;&nbsp;<a href="admin/cars/add_edit?id='+car_id+'">'+cars[car_id]+ '</a>&nbsp;&nbsp;Comment ( <a href="admin/comments?car_id='+car_id+'">View</a>&nbsp;/&nbsp;<a href="admin/comments/add_edit?car_id='+car_id+'">Add</a> )</li>';
                }
             $('.cars_list #car_names').empty();
             $('.cars_list #car_names').append(list);


                 CarForm=document.forms['car_form'];
                 if(CarForm['arr_car_dell[]'].length > 0)
                       CarForm['button_dell'].style.display="block";
           }
         });
    }

function GetCarListClient(model_id, seo_url)
{
$(".loading").ajaxStart(function(){ $(this).show(); });
$(".loading").ajaxComplete(function(){ $(this).hide(); });


 $.ajax({
       type: "POST",
       url: "ajax/get_car_list.php",
       data: "model_id="+model_id,
       success: function(data){
         var cars = jQuery.parseJSON(data);
         var list = "";
         var href_car = "";
         for (var car_id in cars) {
                href_car =(seo_url==0) ? '?act=car&amp;car_id='+car_id : 'car/'+car_id;
                list += '<li id="'+car_id+'"><a href="'+href_car+'">'+cars[car_id]+ '</a></li>';
            }
         $('.cars_list #car_names').empty();
         $('.cars_list #car_names').append(list);
       }
     });


}

function DellCars()
    {
    CarForm=document.forms['car_form'];
    var arr_dell=new Array;
    var str_dell="";

    var counter=0;
    for (var i=0; i < CarForm['arr_car_dell[]'].length; i++)
        if(CarForm['arr_car_dell[]'][i].checked)
            arr_dell[counter++]=CarForm['arr_car_dell[]'][i].value;

    str_dell=arr_dell.join(",");

     $.ajax({
           type: "POST",
           url: "init_ajax.php?script=cars.dell_car_list.php",
           data: "str_dell="+str_dell,
           success: function(msg){
               if(msg="success"){
                for (var j in arr_dell) {
                    $('#car_names #'+arr_dell[j]).remove();
                 }
                }
           }
         });
    }
