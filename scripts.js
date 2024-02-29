window.addEventListener("DOMContentLoaded", function() {
	GlobalTimer = setTimeout(SetOverTime,30000);
	document.querySelector('#order_send').addEventListener("click", SendOrder);
    document.querySelector('#order_cancel').addEventListener("click", CancelOrder);
});
let GlobalOverTime = 0;
let GlobalTimer = 0;
function SetOverTime()
{
	GlobalOverTime = 1;
}
function StopTimer()
{
	clearTimeout(GlobalTimer);
}
function ToggleOrderForm()
{
    if (!document.querySelector("form").classList.contains("hidden_elem"))
    {
        document.querySelector("form").classList.add("hidden_elem");
        document.querySelector(".order_accepted").classList.remove("hidden_elem");
    }
    else
    {
        document.querySelector("form").classList.remove("hidden_elem");
        document.querySelector(".order_accepted").classList.add("hidden_elem");
    }
}
function SendOrder()
{
	let UserName = document.querySelector('#order_name').value;
	let UserMail = document.querySelector('#order_email').value;
	let UserPhone = document.querySelector('#order_tel').value;
	let Price = document.querySelector('#order_price').value;
	if (UserName.length < 2) {alert("Имя не может быть короче 2 символов.");return;}; 
	if (UserMail.length < 5) {alert("Поле email заполнено некорректно.");return;}; 
	if (UserPhone.length < 11) {alert("Поле телефон заполнено некорректно.");return;};
	if (Price.length < 1 || Number(Price) < 0) {alert("Цена должна быть больше 0.");return;};
	StopTimer();
	DataArr = {Timer:GlobalOverTime,UserName:UserName,UserMail:UserMail,UserPhone:UserPhone,Price:Price};
	$.ajax({
		type: "POST",
	    url: "amo.php",
	    dataType: 'json',
	    data: DataArr,
	    success: function(data)
	    {
            if(data == 1)
            {
                ToggleOrderForm();
			    setTimeout(ToggleOrderForm,5000);
            }
            else
            {
                alert(data);
            }
            
	    },
	    error: function(jqXHR, status, e) 
	    {
	        if (status === "timeout") 
	        {  
	            alert("Время ожидания ответа истекло.");
	        } 
	        else 
	        {
	            alert('Сервер не смог обработать запрос. \n'+status+'\n'+e);
	        }	
	    }
	});
}
function CancelOrder() 
{
	alert("Форма закрыта");
}