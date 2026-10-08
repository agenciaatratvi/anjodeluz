const images = [
    "assets/images/cascata-01.jpg",
    "assets/images/cascata-02.jpg",
    "assets/images/cascata-03.jpg",
    "assets/images/cascata-04.jpg",
    "assets/images/cascata-05.jpg",
    "assets/images/cascata-06.jpg",
    "assets/images/cascata-07.jpg",
    "assets/images/cascata-08.jpg"
];

function changeImage(index){
    document.getElementById("mainImage").src = images[index];
    document.querySelectorAll(".thumbnail").forEach((button, i) => { button.classList.toggle("active", i === index); });
}

let paymentCheckInterval = null;
let timerInterval = null;
let currentPaymentCode = null;
let isComboOrder = false; 
let currentSize = '30cm';

// Preços das ofertas sincronizados com config.php.
const variants = {
    '30cm': {
        price: window.cascataPrices["30cm"][1] / 100,
        oldPrice: 109.90,
        comboPrice: window.cascataPrices["30cm"][2] / 100,
        comboOldPrice: 179.80,
        savings: 91.90
    },
    '100cm': {
        price: window.cascataPrices["100cm"][1] / 100,
        oldPrice: 198.80,
        comboPrice: window.cascataPrices["100cm"][2] / 100,
        comboOldPrice: 349.80,
        savings: 199.90
    }
};

const productData = { 
    id: 'guardian-angel-starfall-001', 
    name: 'Cascata de Estrelas do Anjo Guardião™', 
    currency: 'BRL' 
};

function selectSize(size) {
    if (typeof creatingPix !== "undefined" && creatingPix) return;
    currentSize = size;
    const v = variants[size];
    
    document.getElementById('btn-30cm').classList.toggle('active', size === '30cm');
    document.getElementById('btn-100cm').classList.toggle('active', size === '100cm');
    
    const fmt = (val) => val.toFixed(2).replace('.', ',');
    
    document.getElementById('display-old-price').innerText = `De R$ ${fmt(v.oldPrice)}`;
    document.getElementById('display-price').innerText = `R$ ${fmt(v.price)}`;
    document.getElementById('display-combo-old').innerText = `De R$ ${fmt(v.comboOldPrice)}`;
    document.getElementById('display-combo-new').innerText = `R$ ${fmt(v.comboPrice)}`;
    document.getElementById('display-savings').innerText = `💰 ECONOMIZE R$ ${fmt(v.savings)}`;
    document.getElementById('display-sticky-price').innerText = `R$ ${fmt(v.price)}`;
    document.getElementById('display-final-price').innerText = `R$ ${fmt(v.price)}`;
    document.getElementById('display-delivery-value').innerText = `R$ ${fmt(v.price)}`;
    document.getElementById('display-faq-price').innerText = `R$ ${fmt(v.price)}`;
}

function trackEvent(eventName, params = {}) { try { gtag('event', eventName, params); } catch (e) {} }
function trackFacebookEvent(eventName, params = {}, eventID = undefined) { try { if (typeof fbq === 'function') fbq('track', eventName, params, eventID ? {eventID} : {}); } catch (e) {} }
function trackTikTokEvent(eventName, params = {}) { try { ttq.track(eventName, params); } catch (e) {} }

function buy() {
    if (creatingPix || restorePending()) return;
    paidHandled = false;
    isComboOrder = false;
    updateCheckoutDisplay();
    document.getElementById('checkoutModal').classList.add('active');
    
    const v = variants[currentSize];
    trackEvent('add_to_cart', { currency: productData.currency, value: v.price });
    trackFacebookEvent('AddToCart', { value: v.price, currency: productData.currency });
    initiateCheckout(v.price, 1);
    trackTikTokEvent('AddToCart', { value: v.price, currency: productData.currency });
}

function buyCombo() {
    if (creatingPix || restorePending()) return;
    paidHandled = false;
    isComboOrder = true;
    updateCheckoutDisplay();
    document.getElementById('checkoutModal').classList.add('active');
    
    const v = variants[currentSize];
    trackEvent('add_to_cart', { currency: productData.currency, value: v.comboPrice });
    trackFacebookEvent('AddToCart', { value: v.comboPrice, currency: productData.currency });
    initiateCheckout(v.comboPrice, 2);
    trackTikTokEvent('AddToCart', { value: v.comboPrice, currency: productData.currency });
}

function updateCheckoutDisplay() {
    const subtitle = document.getElementById('checkoutSubtitle');
    const orderItems = document.getElementById('orderItems');
    const orderTotal = document.getElementById('orderTotal');
    const orderSavings = document.getElementById('orderSavings');
    const v = variants[currentSize];
    const fmt = (val) => val.toFixed(2).replace('.', ',');
    const sizeText = currentSize === '100cm' ? ' (100 cm)' : ' (30 cm)';
    
    if (isComboOrder) {
        subtitle.textContent = `Preencha seus dados para gerar o PIX de R$ ${fmt(v.comboPrice)} (2 unidades).`;
        orderItems.innerHTML = `
            <div class="order-summary-row"><span>Cascata de Estrelas do Anjo Guardião™${sizeText}</span><span class="value">2x</span></div>
            <div class="order-summary-row"><span>De: <s style="color:#888;">R$ ${fmt(v.comboOldPrice)}</s></span><span class="value" style="color:#a71927;">R$ ${fmt(v.comboPrice)}</span></div>
        `;
        orderTotal.textContent = `R$ ${fmt(v.comboPrice)}`;
        orderSavings.innerHTML = `<span>💰 Você economiza</span><span class="value">R$ ${fmt(v.savings)}</span>`;
    } else {
        subtitle.textContent = `Preencha seus dados para gerar o PIX de R$ ${fmt(v.price)}.`;
        orderItems.innerHTML = `
            <div class="order-summary-row"><span>Cascata de Estrelas do Anjo Guardião™${sizeText}</span><span class="value">1x</span></div>
            <div class="order-summary-row"><span>De: <s style="color:#888;">R$ ${fmt(v.oldPrice)}</s></span><span class="value" style="color:#a71927;">R$ ${fmt(v.price)}</span></div>
        `;
        orderTotal.textContent = `R$ ${fmt(v.price)}`;
        orderSavings.innerHTML = `<span>💰 Você economiza</span><span class="value">R$ ${fmt(v.oldPrice - v.price)}</span>`;
    }
}

function closeModal() {
    if (creatingPix) return;
    document.getElementById('checkoutModal').classList.remove('active');
    document.getElementById('step1').style.display = 'block';
    document.getElementById('step2').style.display = 'none';
    document.getElementById('successStep').style.display = 'none';
    document.getElementById('loadingStep').style.display = 'none';
    document.getElementById('pixForm').reset();
    document.getElementById('deliveryNotice').classList.remove('show');
    const checkingEl = document.getElementById('checkingPayment');
    if (checkingEl) { checkingEl.innerHTML = 'Aguardando confirmação do pagamento...'; checkingEl.style.animation = 'pulse 2s infinite'; checkingEl.style.color = '#666'; }
    if (paymentCheckInterval) { clearInterval(paymentCheckInterval); paymentCheckInterval = null; }
    if (timerInterval) { clearInterval(timerInterval); timerInterval = null; }
    currentPaymentCode = null;
    isComboOrder = false;
}

function formatPhone(value) {
    let digits = value.replace(/\D/g, '');
    if ((digits.length === 12 || digits.length === 13) && digits.startsWith('55')) digits = digits.slice(2);
    digits = digits.slice(0, 11);
    if (digits.length > 6) { const tail = digits.slice(2); return `(${digits.slice(0,2)}) ${tail.slice(0,-4)}-${tail.slice(-4)}`; }
    if (digits.length > 2) return `(${digits.slice(0,2)}) ${digits.slice(2)}`;
    if (digits.length) return `(${digits}`;
    return '';
}

function formatCep(value) {
    value = value.replace(/\D/g, '');
    if (value.length > 8) value = value.substring(0, 8);
    if (value.length > 5) return `${value.substring(0,5)}-${value.substring(5)}`;
    return value;
}

document.getElementById('customerPhone').addEventListener('input', function(e) { e.target.value = formatPhone(e.target.value); });
document.getElementById('shippingZip').addEventListener('input', function(e) { e.target.value = formatCep(e.target.value); });
document.getElementById('customerDocument').addEventListener('input', function(e) { e.target.value = e.target.value.replace(/\D/g, '').slice(0,11); });

const cepInput = document.getElementById('shippingZip');
const deliveryNotice = document.getElementById('deliveryNotice');
let isFetchingCep = false;

cepInput.addEventListener('input', async function(e) {
    let cep = e.target.value.replace(/\D/g, '');
    if (cep.length === 8 && !isFetchingCep) {
        isFetchingCep = true;
        e.target.placeholder = 'Buscando...';
        e.target.disabled = true;
        try {
            const response = await fetch(`https://viacep.com.br/ws/${cep}/json/`);
            const data = await response.json();
            if (!data.erro) {
                document.getElementById('shippingStreet').value = data.logradouro;
                document.getElementById('shippingNeighborhood').value = data.bairro;
                document.getElementById('shippingCity').value = data.localidade;
                document.getElementById('shippingState').value = data.uf;
                deliveryNotice.classList.add('show');
                setTimeout(() => document.getElementById('shippingNumber').focus(), 100);
            } else {
                deliveryNotice.classList.remove('show');
            }
        } catch (error) {
            deliveryNotice.classList.remove('show');
        } finally {
            e.target.placeholder = '00000-000';
            e.target.disabled = false;
            isFetchingCep = false;
        }
    }
});

function startPixTimer(minutes = 20) {
    let totalSeconds = Math.floor(minutes * 60);
    const timerEl = document.getElementById('pixTimer');
    if (timerInterval) clearInterval(timerInterval);
    timerEl.classList.remove('expired');
    timerEl.style.color = '#a71927';
    const updateTimer = () => {
        const mins = Math.floor(totalSeconds / 60);
        const secs = totalSeconds % 60;
        timerEl.textContent = `${String(mins).padStart(2,'0')}:${String(secs).padStart(2,'0')}`;
        if (totalSeconds <= 60) timerEl.style.color = '#d32f2f';
        if (totalSeconds <= 0) {
            clearInterval(timerInterval);
            timerEl.textContent = 'EXPIRADO';
            timerEl.classList.add('expired');
            const checkingEl = document.getElementById('checkingPayment');
            if (checkingEl) { checkingEl.innerHTML = '<span style="color:#d32f2f;">⚠ Este código PIX expirou. Feche e gere um novo.</span>'; checkingEl.style.animation = 'none'; }
            if (paymentCheckInterval) { clearInterval(paymentCheckInterval); paymentCheckInterval = null; }
            return;
        }
        totalSeconds--;
    };
    updateTimer();
    timerInterval = setInterval(updateTimer, 1000);
}

// NOVO PAGAMENTO
let pendingOrder = null;
let creatingPix = false;
let checkingPix = false;
let requestId = null;
let paidHandled = false;
const pendingKey = 'cascata_pending_order';
const storageGet = (key) => { try { return sessionStorage.getItem(key); } catch { return null; } };
const storageSet = (key, value) => { try { sessionStorage.setItem(key, value); } catch {} };
const storageRemove = (key) => { try { sessionStorage.removeItem(key); } catch {} };
function paymentView(row) {
    pendingOrder = row;
    currentPaymentCode = row.id;
    document.getElementById('step1').style.display = 'none';
    document.getElementById('loadingStep').style.display = 'none';
    document.getElementById('successStep').style.display = 'none';
    document.getElementById('step2').style.display = 'block';
    document.getElementById('pixCopyPaste').value = row.pixCode;
    const qr = qrcode(0, 'M'); qr.addData(row.pixCode); qr.make();
    const image = document.getElementById('qrCodeImage');
    image.src = qr.createDataURL(6, 4); image.style.display = 'block';
    document.getElementById('checkingPayment').textContent = 'Aguardando confirmação do pagamento...';
    const seconds = Math.max(0, Math.floor((Date.parse(row.expiresAt) - Date.now()) / 1000));
    startPixTimer(seconds / 60);
    if (paymentCheckInterval) clearInterval(paymentCheckInterval);
    paymentCheckInterval = setInterval(checkPaymentStatus, 10000);
    checkPaymentStatus();
}
async function checkPaymentStatus() {
    if (!pendingOrder || checkingPix || paidHandled) return;
    const row = pendingOrder;
    checkingPix = true;
    try {
        const response = await fetch('api/status.php?id=' + encodeURIComponent(row.id), {
            headers: {'X-Order-Token': row.token}, cache: 'no-store'
        });
        const result = await response.json();
        if (!response.ok) throw new Error(result.error || 'Falha na consulta.');
        if (!pendingOrder || pendingOrder.id !== row.id) return;
        if (result.status === 'paid') {
            paidHandled = true;
            clearInterval(paymentCheckInterval); clearInterval(timerInterval);
            paymentCheckInterval = timerInterval = null;
            document.getElementById('step2').style.display = 'none';
            document.getElementById('successStep').style.display = 'block';
            const eventId = 'purchase_' + row.id;
            let alreadySent = false;
            try { alreadySent = !!localStorage.getItem(eventId); } catch {}
            if (!alreadySent) {
                trackFacebookEvent('Purchase', {
                    value: result.amount / 100, currency: 'BRL',
                    content_ids: [productData.id + '-' + row.size], content_type: 'product',
                    contents: [{id: productData.id + '-' + row.size, quantity: row.quantity}],
                    num_items: row.quantity, order_id: row.id
                }, eventId);
                try { localStorage.setItem(eventId, '1'); } catch {}
            }
            storageRemove(pendingKey);
            const details = document.querySelector('#successStep .success-details');
            if (details && !details.querySelector('.order-reference')) {
                const p = document.createElement('p'); p.className = 'order-reference'; p.textContent = 'Pedido: ' + row.id; details.prepend(p);
            }
        } else if (['failed','refused','expired','cancelled','refunded','chargeback'].includes(result.status)) {
            clearInterval(paymentCheckInterval); clearInterval(timerInterval);
            document.getElementById('checkingPayment').textContent = 'Esta cobrança foi encerrada (' + result.status + ').';
            document.getElementById('pixTimer').textContent = 'ENCERRADO';
            storageRemove(pendingKey); pendingOrder = null; requestId = null;
        }
    } catch (error) {
        document.getElementById('checkingPayment').textContent = 'Aguardando confirmação. A consulta será repetida automaticamente.';
    } finally { checkingPix = false; }
}
async function generatePix(event) {
    event.preventDefault();
    if (creatingPix) return;
    if (!document.getElementById('pixForm').reportValidity()) return;
    creatingPix = true;
    requestId ||= storageGet('cascata_request_id') || (crypto.randomUUID ? crypto.randomUUID() : Date.now().toString(36) + '-' + Math.random().toString(36).slice(2) + '-order');
    storageSet('cascata_request_id', requestId);
    const value = id => document.getElementById(id).value.trim();
    const tracking = {};
    ['utm_source','utm_medium','utm_campaign','utm_content','utm_term','src','sck','xcod','fbclid','gclid','ttclid'].forEach(key => { tracking[key] = storageGet(key); });
    const payload = {requestId, size: currentSize, quantity: isComboOrder ? 2 : 1,
        customer: {name:value('customerName'), email:value('customerEmail'), phone:value('customerPhone'), document:value('customerDocument')},
        address: {zip:value('shippingZip'), street:value('shippingStreet'), number:value('shippingNumber'), complement:value('shippingComplement'), neighborhood:value('shippingNeighborhood'), city:value('shippingCity'), state:value('shippingState')}, tracking};
    document.getElementById('step1').style.display = 'none';
    document.getElementById('loadingStep').style.display = 'block';
    try {
        const response = await fetch('api/pix.php', {method:'POST', headers:{'Content-Type':'application/json','X-CSRF-Token':document.querySelector('meta[name="csrf-token"]').content}, body:JSON.stringify(payload)});
        const result = await response.json();
        if (!response.ok) { if(response.status === 422) { requestId = null; storageRemove('cascata_request_id'); } throw new Error(result.error || 'Não foi possível gerar o PIX.'); }
        if (!result.pixCode || !result.id || !result.token) throw new Error('Resposta PIX incompleta.');
        storageSet(pendingKey, JSON.stringify(result)); storageRemove('cascata_request_id'); requestId = null; paidHandled = false;
        trackFacebookEvent('AddPaymentInfo', {value:result.amount / 100,currency:'BRL'}, 'payment_' + result.id);
        paymentView(result);
    } catch (error) {
        alert(error.message);
        document.getElementById('loadingStep').style.display = 'none';
        document.getElementById('step1').style.display = 'block';
    } finally { creatingPix = false; }
}
function restorePending() {
    if (paidHandled) { pendingOrder = null; return false; }
    if (!pendingOrder) { try { pendingOrder = JSON.parse(storageGet(pendingKey) || 'null'); } catch {} }
    if (pendingOrder && pendingOrder.id && pendingOrder.token && pendingOrder.pixCode) {
        document.getElementById('checkoutModal').classList.add('active');
        paymentView(pendingOrder); return true;
    }
    return false;
}


async function copyPixCode() {
 const input = document.getElementById('pixCopyPaste');
 if (!input.value) return;
 const button = document.getElementById('btnCopyPix');
 try {
  if (navigator.clipboard && window.isSecureContext) await navigator.clipboard.writeText(input.value);
  else { input.style.position='fixed';input.style.left='0';input.style.opacity='.01';input.select();if (!document.execCommand('copy')) throw new Error('copy');input.style.position='absolute';input.style.left='-9999px'; }
  const text=button.innerHTML;button.textContent='✅ CÓDIGO COPIADO!';setTimeout(()=>button.innerHTML=text,2500);
 } catch { input.style.position='static';input.style.opacity='1';input.style.width='100%';input.select();alert('Selecione e copie o código mostrado abaixo.'); }
}

(function captureUTMs() {
    const params = new URLSearchParams(window.location.search);
    ['utm_source','utm_medium','utm_campaign','utm_content','utm_term','src','sck','xcod','fbclid','gclid','ttclid'].forEach(key => {
        const val = params.get(key);
        if (val) storageSet(key, val);
    });
})();

let globalTimerSeconds = 2 * 60 * 60;
function updateGlobalTimer() {
    const timerEl = document.getElementById('globalOfferTimer');
    if (!timerEl) return;
    const hours = Math.floor(globalTimerSeconds / 3600);
    const mins = Math.floor((globalTimerSeconds % 3600) / 60);
    const secs = globalTimerSeconds % 60;
    timerEl.textContent = `${String(hours).padStart(2,'0')}:${String(mins).padStart(2,'0')}:${String(secs).padStart(2,'0')}`;
    if (globalTimerSeconds <= 0) { timerEl.textContent = '00:00:00'; return; }
    globalTimerSeconds--;
}
setInterval(updateGlobalTimer, 1000);
updateGlobalTimer();


selectSize('30cm');

trackFacebookEvent("ViewContent", {content_ids:[productData.id],content_type:"product",value:window.cascataPrices["30cm"][1]/100,currency:"BRL"});
restorePending();

function initiateCheckout(value, quantity) {
    const eventId = 'checkout_' + (crypto.randomUUID ? crypto.randomUUID() : Date.now().toString(36) + '-' + Math.random().toString(36).slice(2));
    trackFacebookEvent('InitiateCheckout', {value,currency:'BRL',content_ids:[productData.id+'-'+currentSize],content_type:'product',num_items:quantity}, eventId);
    const tracking = {}; ['fbclid','utm_source','utm_campaign','utm_medium','utm_content','utm_term'].forEach(key => { tracking[key] = storageGet(key); });
    const payload = {eventId,size:currentSize,quantity,tracking};
    const send = () => fetch('api/checkout-event.php', {method:'POST',keepalive:true,headers:{'Content-Type':'application/json','X-CSRF-Token':document.querySelector('meta[name="csrf-token"]').content},body:JSON.stringify(payload)});
    send().then(response => { if (!response.ok && response.status >= 500) setTimeout(() => send().catch(()=>{}), 12000); }).catch(()=>{});
}
