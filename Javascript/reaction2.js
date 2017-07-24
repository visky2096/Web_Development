var start ;
			  function getRandomColor() 	
			{
        		var letters = '0123456789ABCDEF'.split('');
        		var color = '#';
        		for (var i = 0; i < 6; i++ ) 
        		{
            	color += letters[Math.floor(Math.random() * 16)];
        		}
        		return color;
    		}


			function makeshapeappear()
			{
				var top=Math.random()*100;
				var left=Math.random()*780;
				var width=[Math.random()*400]+100;
				if(Math.random()>0.5)
				{
					document.getElementById("shape").style.borderRadius="50%";
				}
				else
				{
					document.getElementById("shape").style.borderRadius="0";
				}
				document.getElementById("shape").style.width=width+"px";
				document.getElementById("shape").style.backgroundColor=getRandomColor();
				document.getElementById("shape").style.top=top +"px";
				document.getElementById("shape").style.left=left +"px";
				document.getElementById("shape").style.display="block";
				start = new Date().getTime();
			}

			function appearafterdelay()
			{
				setTimeout(makeshapeappear,Math.random()*2000);
			}
				appearafterdelay();
				var besttiming="-";
				document.getElementById("shape").onclick=function()
			{
				var end = new Date().getTime();
				var currenttiming=(end-start)/1000;
				document.getElementById("shape").style.display="none";
				document.getElementById("current").innerHTML=currenttiming+"s";
				if((currenttiming<besttiming)||besttiming=="-")
				{
					besttiming=currenttiming;
					document.getElementById("thebest").innerHTML=besttiming+"s";
				}
				
			
				appearafterdelay();
			}
			