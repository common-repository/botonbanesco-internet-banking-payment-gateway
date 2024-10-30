var _LOADING_ = "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";
var _BOTON_   = "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";
const Banesco_body = document.querySelector('body');
const Banesco_overlayDiv = document.createElement('div');
let Banesco_displayBool = false;

Banesco_overlayDiv.style.position = 'fixed';
Banesco_overlayDiv.style.width = '100%';
Banesco_overlayDiv.style.height = '100%';
Banesco_overlayDiv.style.top = '0';
Banesco_overlayDiv.style.left = '0';
Banesco_overlayDiv.style.right = '0';
Banesco_overlayDiv.style.bottom = '0';
Banesco_overlayDiv.style.backgroundColor = 'rgba(0,0,0,0.5)';
Banesco_overlayDiv.style.zIndex = '2';
Banesco_overlayDiv.style.textAlign = '-webkit-center';
Banesco_overlayDiv.style.display = "none";



function Banesco_styleOverlay() {
    const Banesco_contentContainer = document.querySelector('#Banesco_DIVIframe');
    Banesco_contentContainer.style.textAlign = 'center';
    Banesco_contentContainer.style.marginTop = '0';
    Banesco_contentContainer.style.backgroundColor = '#5f685c';
    Banesco_contentContainer.style.width = '100%';
    Banesco_contentContainer.style.height = '100%';
    Banesco_contentContainer.style.display = 'block';
    Banesco_contentContainer.style.zIndex = '99999999999999';
    Banesco_contentContainer.style.position = 'fixed';
    Banesco_contentContainer.style.opacity = '1';

}

function Banesco_boton(url, cedula, monto, idtramite, comprobante, concepto, apikey, firma, tipo) {
	
    if (idtramite.length <= 32 && comprobante.length <= 22) {
        return `<img style='cursor: pointer;' id='banesco_img_boton' src='data:image/gif;base64, ` + _BOTON_ + `'  onclick='Banesco_open_win("` + url + `","`
            + cedula + `","` + monto + `","`
            + idtramite + `","` + comprobante + `","`
            + concepto + `","` + apikey + `","`
            + firma + `", 1 )'>`;
    } else {
        return "<span>Error en datos </span>"
    };
}

function Banesco_Cancelar2(event) {
    
    var datos=event.data;
    if (datos != "") {
        const cmd = datos.split("|");
		if(cmd.length>=2)
		{
		 switch(cmd[0]) {
		    case "r":
              window.location.href=cmd[1];
              break;			  
		 }
		}
    }
}
function Banesco_open_win(url, cedula, monto, idtramite, comprobante, concepto, apikey, firma, tipo) {

    if (tipo == 1) {
		window.addEventListener("message", Banesco_Cancelar2, false);
        Banesco_window = window.open("Boton de Pago Banesco", "Banesco_Boton", "dialog=yes,status=no,toolbar=no,location=no,menubar=no,scrollbars=no,resizable=no,fullscreen=no,height=" + (screen.height * 0.8) + "px, width = 470px");
        //var loading= _LOADING_;
        var html = "<div style='background-color:#00795354;height:100%;'><div style='top:50%;left:35%;position:absolute;'><img src='data:image/gif;base64, " + _LOADING_ + "' /><br><span>Conectando ...</span></div></div>";
        Banesco_window.document.write(html);
        //Hidden Form
        var banesco_form = document.createElement("form");
        banesco_form.setAttribute("method", "post");
        banesco_form.setAttribute("action", url);
        banesco_form.setAttribute("target", "Banesco_Boton");

        //Hidden Field
        var banesco_formhiddenField1 = document.createElement("input");
        var banesco_formhiddenField2 = document.createElement("input");
        var banesco_formhiddenField3 = document.createElement("input");
        var banesco_formhiddenField4 = document.createElement("input");
        var banesco_formhiddenField5 = document.createElement("input");
        var banesco_formhiddenField6 = document.createElement("input");
        var banesco_formhiddenField7 = document.createElement("input");
        var banesco_formhiddenField8 = document.createElement("input");

        //cedula
        banesco_formhiddenField1.setAttribute("type", "hidden");
        banesco_formhiddenField1.setAttribute("id", "valor1");
        banesco_formhiddenField1.setAttribute("name", "valor1");
        banesco_formhiddenField1.setAttribute("value", cedula);
        //monto
        banesco_formhiddenField2.setAttribute("type", "hidden");
        banesco_formhiddenField2.setAttribute("id", "valor2");
        banesco_formhiddenField2.setAttribute("name", "valor2");
        banesco_formhiddenField2.setAttribute("value", monto);

        //idtramite
        banesco_formhiddenField3.setAttribute("type", "hidden");
        banesco_formhiddenField3.setAttribute("id", "valor3");
        banesco_formhiddenField3.setAttribute("name", "valor3");
        banesco_formhiddenField3.setAttribute("value", idtramite);

        //comprobante
        banesco_formhiddenField4.setAttribute("type", "hidden");
        banesco_formhiddenField4.setAttribute("id", "valor4");
        banesco_formhiddenField4.setAttribute("name", "valor4");
        banesco_formhiddenField4.setAttribute("value", comprobante);

        //concepto
        banesco_formhiddenField5.setAttribute("type", "hidden");
        banesco_formhiddenField5.setAttribute("id", "valor5");
        banesco_formhiddenField5.setAttribute("name", "valor5");
        banesco_formhiddenField5.setAttribute("value", concepto);

        //apikey
        banesco_formhiddenField6.setAttribute("type", "hidden");
        banesco_formhiddenField6.setAttribute("id", "apikey");
        banesco_formhiddenField6.setAttribute("name", "apikey");
        banesco_formhiddenField6.setAttribute("value", apikey);

        //firma
        banesco_formhiddenField7.setAttribute("type", "hidden");
        banesco_formhiddenField7.setAttribute("id", "firma");
        banesco_formhiddenField7.setAttribute("name", "firma");
        banesco_formhiddenField7.setAttribute("value", firma);

        //tipo
        banesco_formhiddenField8.setAttribute("type", "hidden");
        banesco_formhiddenField8.setAttribute("id", "tipo");
        banesco_formhiddenField8.setAttribute("name", "tipo");
        banesco_formhiddenField8.setAttribute("value", tipo);


        banesco_form.appendChild(banesco_formhiddenField1);
        banesco_form.appendChild(banesco_formhiddenField2);
        banesco_form.appendChild(banesco_formhiddenField3);
        banesco_form.appendChild(banesco_formhiddenField4);
        banesco_form.appendChild(banesco_formhiddenField5);
        banesco_form.appendChild(banesco_formhiddenField6);
        banesco_form.appendChild(banesco_formhiddenField7);
        banesco_form.appendChild(banesco_formhiddenField8);

        document.body.appendChild(banesco_form);
        banesco_form.submit();
    }
    else {
        window.addEventListener("message", Banesco_Cancelar, false);
        params = "cedula=" + cedula;
        params += "&firma=" + firma;
        params += "&monto=" + monto;
        params += "&idtramite=" + idtramite;
        params += "&comprobante=" + comprobante;
        params += "&concepto=" + concepto;
        params += "&apikey=" + apikey;
        params += "&tipo=" + tipo;
        params = "?" + params;
        var banesco_div = document.createElement("div");
        banesco_div.setAttribute("id", "Banesco_DIVIframe");
        banesco_div.setAttribute("position", "relative");
        banesco_div.setAttribute("style", "background-color: rgba(255,255,255,.5)");

        var banesco_iframe = document.createElement("iframe");
        banesco_iframe.setAttribute("id", "Banesco_Iframe");
        banesco_iframe.setAttribute("src", url + params);
        banesco_iframe.setAttribute("title", "Boton de pagos Banesco ");
        banesco_iframe.setAttribute("width", "60%");
        banesco_iframe.setAttribute("height", "70%");
        banesco_iframe.setAttribute("allow", "payment");
        banesco_iframe.setAttribute("scrolling", "no");
        banesco_iframe.setAttribute("style", " margin-top : 15px");

        banesco_div.appendChild(banesco_iframe);

        //document.body.appendChild(banesco_div);
        document.body.prepend(banesco_div);
        Banesco_styleOverlay();
    }

  
}
function woocommerce_botonbanesco_BOTONBANESCO_ENV_change() {
	   
	   woocommerce_botonbanesco_BOTONBANESCO_ENV = document.getElementById("woocommerce_botonbanesco_BOTONBANESCO_ENV");
	   woocommerce_botonbanesco_BOTONBANESCO_SERVER = document.getElementById("woocommerce_botonbanesco_BOTONBANESCO_SERVER");
	   
	   if(woocommerce_botonbanesco_BOTONBANESCO_ENV.value ==  "qa") 
		  woocommerce_botonbanesco_BOTONBANESCO_SERVER.value="https://qa-botondepago.banescopagos.com";
	   else
		  woocommerce_botonbanesco_BOTONBANESCO_SERVER.value="https://botondepago.banescopagos.com"; 
	   return true;
}

