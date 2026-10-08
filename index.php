<?php
require __DIR__ . '/api/bootstrap.php';
$csrf = csrf_token();
?>
<!DOCTYPE html><html lang="pt-BR"><head><meta charset="UTF-8"><meta name="viewport" content="width=device-width,initial-scale=1.0"><title>Cascata de Estrelas do Anjo Guardião™</title><meta name="description" content="Cascata de Estrelas do Anjo Guardião™. Decoração de parede iluminada com fé e bênção."><meta name="csrf-token" content="<?= htmlspecialchars($csrf, ENT_QUOTES, 'UTF-8') ?>"><link rel="stylesheet" href="assets/style.css"><link rel="icon" href="assets/favicon.svg"><style>[hidden]{display:none!important}</style></head><body class="wp-singular page-template page-template-elementor_canvas page page-id-576 wp-embed-responsive wp-theme-twentytwentyfive elementor-default elementor-template-canvas elementor-kit-4 elementor-page elementor-page-576">
			<div data-elementor-type="wp-page" data-elementor-id="576" class="elementor elementor-576">
				<div class="elementor-element elementor-element-8022a20 e-flex e-con-boxed e-con e-parent" data-id="8022a20" data-element_type="container" data-e-type="container">
					<div class="e-con-inner">
				<div class="elementor-element elementor-element-0a9ad53 elementor-widget elementor-widget-html" data-id="0a9ad53" data-element_type="widget" data-e-type="widget" data-widget_type="html.default">
					







<!-- Google tag (gtag.js) -->



<!-- Facebook Pixel Code -->



<!-- TikTok Pixel Code -->


<!-- UTMify -->





<body>
<div class="page">
    <div class="offer-timer-bar">
        <span class="timer-icon">🔥</span>
        <span>OFERTA TERMINA EM:</span>
        <span class="timer-value" id="globalOfferTimer">02:00:00</span>
    </div>
    <div class="topbar">FRETE GRÁTIS • OFERTA ESPECIAL</div>

    <div class="gallery">
        <img decoding="async" id="mainImage" class="main-image" src="assets/images/cascata-01.jpg" alt="Cascata de Estrelas do Anjo Guardião" ondragstart="return false;">
        <div class="thumbnails">
            <button class="thumbnail active" onclick="changeImage(0)"><img decoding="async" src="assets/images/cascata-01.jpg" alt="Cascata de Estrelas" ondragstart="return false;"></button>
            <button class="thumbnail" onclick="changeImage(1)"><img decoding="async" src="assets/images/cascata-02.jpg" alt="Cascata de Estrelas" ondragstart="return false;"></button>
            <button class="thumbnail" onclick="changeImage(2)"><img decoding="async" src="assets/images/cascata-03.jpg" alt="Cascata de Estrelas" ondragstart="return false;"></button>
            <button class="thumbnail" onclick="changeImage(3)"><img decoding="async" src="assets/images/cascata-04.jpg" alt="Cascata de Estrelas" ondragstart="return false;"></button>
            <button class="thumbnail" onclick="changeImage(4)"><img decoding="async" src="assets/images/cascata-05.jpg" alt="Cascata de Estrelas" ondragstart="return false;"></button>
            <button class="thumbnail" onclick="changeImage(5)"><img decoding="async" src="assets/images/cascata-06.jpg" alt="Cascata de Estrelas" ondragstart="return false;"></button>
            <button class="thumbnail" onclick="changeImage(6)"><img decoding="async" src="assets/images/cascata-07.jpg" alt="Cascata de Estrelas" ondragstart="return false;"></button>
            <button class="thumbnail" onclick="changeImage(7)"><img decoding="async" src="assets/images/cascata-08.jpg" alt="Cascata de Estrelas" ondragstart="return false;"></button>
        </div>
    </div>

    <div class="product">
        <div class="product-tag">OFERTA ESPECIAL</div>
        <h1>✨ Cascata de Estrelas do Anjo Guardião™ – Decoração de Parede Iluminada com Fé e Bênção</h1>
        <p class="description">Uma cascata luminosa que transforma qualquer ambiente em um espaço sagrado de paz, fé e proteção divina durante o Natal.</p>
        
        <div class="size-selector">
            <span class="size-label">Escolha o tamanho:</span>
            <div class="size-options">
                <button class="size-btn active" onclick="selectSize('30cm')" id="btn-30cm">
                    30 cm <span>(Padrão)</span>
                </button>
                <button class="size-btn" onclick="selectSize('100cm')" id="btn-100cm">
                    100 cm <span>(Gigante)</span>
                </button>
            </div>
        </div>

        <div class="price-card">
            <div class="old-price" id="display-old-price">De R$ 109,90</div>
            <div class="price-line">
                <div class="price" id="display-price">R$ 47,90</div>
                <div class="discount">OFERTA</div>
            </div>
            <div class="price-info">Condição especial da oferta</div>
            <div class="stock-counter" hidden>
                <div class="stock-icon">⚠️</div>
                <div class="stock-text">RESTAM APENAS <span class="stock-number" id="stockCount">7</span> UNIDADES EM ESTOQUE</div>
            </div>
        </div>

        <div class="combo-offer-card">
            <div class="combo-title">🎄 LEVE 2 UNIDADES</div>
            <div class="combo-price-old" id="display-combo-old">De R$ 179,80</div>
            <div class="combo-price-new" id="display-combo-new">R$ 87,90</div>
            <div class="combo-savings" id="display-savings">💰 ECONOMIZE R$ 91,90</div>
            <div class="combo-description">Perfeito para presentear ou decorar múltiplos ambientes da sua casa!</div>
            <button class="combo-button" onclick="buyCombo()">QUERO 2 UNIDADES</button>
        </div>

        <div class="offer">FRETE GRÁTIS • ENTREGA ESTIMADA EM 6 A 11 DIAS</div>
        <button class="buy-button" onclick="buy()">COMPRAR 1 UNIDADE AGORA</button>
        <div class="payment-info">Pagamento 100% via PIX • Aprovação Imediata</div>
        <div class="guarantee-badge">
            <div class="guarantee-icon">🛡️</div>
            <div class="guarantee-title">GARANTIA DE 7 DIAS</div>
            <div class="guarantee-text">Não gostou do produto? Devolvemos 100% do seu dinheiro.<br><strong>Compra 100% segura e sem riscos!</strong></div>
        </div>
    </div>

    <div class="trust">
        <div class="trust-item"><span class="trust-title">Frete grátis</span>Envio informado na loja</div>
        <div class="trust-item"><span class="trust-title">Rastreio</span>Código de acompanhamento</div>
        <div class="trust-item"><span class="trust-title">Pagamento</span>Exclusivo via PIX</div>
    </div>

    <section class="section">
        <h2>Uma cascata de luz e bênçãos para o seu lar</h2>
        <p>A Cascata de Estrelas do Anjo Guardião™ cria um efeito visual deslumbrante que parece uma chuva de estrelas abençoando seu ambiente, trazendo paz e espiritualidade para o Natal.</p>
        <img decoding="async" class="section-image" src="assets/images/cascata-02.jpg" alt="Cascata de Estrelas do Anjo Guardião" ondragstart="return false;">
    </section>

    <section class="section">
        <h2>Por que escolher a Cascata de Estrelas?</h2>
        <ul class="benefits">
            <li><strong>Efeito cascata único</strong>Design exclusivo que simula uma chuva de estrelas iluminadas.</li>
            <li><strong>Ambiente sagrado e acolhedor</strong>Cria uma atmosfera de paz, fé e proteção divina.</li>
            <li><strong>Iluminação LED suave</strong>Luzes delicadas que não ofuscam, apenas embelezam.</li>
            <li><strong>Fácil instalação</strong>Funciona com bateria, sem fios aparentes ou tomadas.</li>
            <li><strong>Presente especial</strong>Uma peça única para presentear quem você ama.</li>
        </ul>
        <img decoding="async" class="section-image" src="assets/images/cascata-03.jpg" alt="Cascata de Estrelas do Anjo Guardião" ondragstart="return false;">
    </section>

    <section class="section">
        <h2>Veja alguns detalhes</h2>
        <p>Confira as imagens do produto e visualize como ele pode fazer parte da decoração do seu ambiente.</p>
        <img decoding="async" class="section-image" src="assets/images/cascata-04.jpg" alt="Cascata de Estrelas do Anjo Guardião" ondragstart="return false;">
        <img decoding="async" class="section-image" src="assets/images/cascata-05.jpg" alt="Cascata de Estrelas do Anjo Guardião" ondragstart="return false;">
    </section>

    <section class="section">
        <h2>Entrega e pagamento</h2>
        <div class="delivery-box">
            <div class="delivery-row"><span>Produto</span><strong>Cascata de Estrelas do Anjo Guardião™</strong></div>
            <div class="delivery-row"><span>Valor</span><strong id="display-delivery-value">R$ 47,90</strong></div>
            <div class="delivery-row"><span>Frete</span><strong>Grátis</strong></div>
            <div class="delivery-row"><span>Prazo informado</span><strong>6 a 11 dias</strong></div>
            <div class="delivery-row"><span>Pagamento</span><strong>Via PIX</strong></div>
            <div class="delivery-row"><span>Rastreamento</span><strong>Disponível</strong></div>
        </div>
    </section>

    <section class="section faq">
        <h2>Perguntas frequentes</h2>
        <details open><summary>Qual é o valor do produto?</summary><p>O valor apresentado nesta oferta é de <strong id="display-faq-price">R$ 47,90</strong>.</p></details>
        <details><summary>A Cascata funciona com bateria?</summary><p>Sim! Funciona com bateria, sem necessidade de fios ou tomadas, facilitando a instalação em qualquer lugar.</p></details>
        <details><summary>O frete é grátis?</summary><p>Sim. A oferta informa frete grátis.</p></details>
        <details><summary>Qual é o prazo de entrega?</summary><p>O prazo informado para entrega é de <strong>6 a 11 dias</strong>.</p></details>
        <details><summary>Vou receber código de rastreamento?</summary><p>Sim. A oferta informa envio com código de rastreamento.</p></details>
        <details><summary>Quais formas de pagamento estão disponíveis?</summary><p>Aceitamos pagamento exclusivo via <strong>PIX</strong>, garantindo aprovação imediata e processamento mais rápido do seu pedido.</p></details>
    </section>

    <section class="section">
        <div class="final-box">
            <h2>Garanta sua Cascata de Estrelas do Anjo Guardião™</h2>
            <p>Aproveite a condição especial de <strong id="display-final-price">R$ 47,90</strong> com frete grátis.</p>
            <button class="buy-button" onclick="buy()">QUERO MINHA CASCATA DE ESTRELAS</button>
        </div>
    </section>

    <footer class="footer">
        <strong>SHOP TIKTOK</strong><br><br>
        Consulte as informações do produto, prazo e condições antes de finalizar sua compra.
    </footer>
</div>

<div class="sticky">
    <div class="sticky-inner">
        <div class="sticky-price" id="display-sticky-price">R$ 47,90</div>
        <button class="buy-button" onclick="buy()">COMPRAR AGORA</button>
    </div>
</div>

<div id="checkoutModal" class="modal-overlay">
    <div class="modal-content">
        <button class="modal-close" onclick="closeModal()">&times;</button>
        
        <div id="step1">
            <div class="pix-icon-large">
                <div class="pix-logo">PIX</div>
                <div class="pix-subtitle">Pagamento seguro com aprovação imediata</div>
            </div>
            <h3>Finalizar Compra</h3>
            <p id="checkoutSubtitle">Preencha seus dados para gerar o PIX.</p>
            
            <div class="order-summary" id="orderSummary">
                <div class="order-summary-title">📦 Resumo do Pedido</div>
                <div id="orderItems"></div>
                <div class="order-summary-row"><span>Frete</span><span class="value" style="color:#28a745;">GRÁTIS</span></div>
                <div class="order-summary-row total"><span>Total</span><span class="value" id="orderTotal">R$ 47,90</span></div>
                <div class="order-summary-row savings" id="orderSavings"><span>💰 Você economiza</span><span class="value">R$ 62,00</span></div>
            </div>
            
            
            
            <form id="pixForm" onsubmit="generatePix(event)">
                <input type="text" id="customerName" placeholder="Nome completo" required>
                <input type="email" id="customerEmail" placeholder="E-mail" required>
                <input type="tel" id="customerPhone" placeholder="(11) 99999-9999" required maxlength="25">
                <input type="text" id="customerDocument" placeholder="CPF (apenas números)" required maxlength="18">
                
                <div class="divider">
                    <div class="divider-title">Endereço de Entrega</div>
                    <input type="text" id="shippingZip" placeholder="00000-000" required maxlength="9">
                    <div id="deliveryNotice" class="delivery-notice">🚚 Entrega via Correios • <strong>6 a 11 dias úteis</strong> após confirmação</div>
                    <input type="text" id="shippingStreet" placeholder="Endereço (Rua, Avenida)" required>
                    <div class="flex-row">
                        <input type="text" id="shippingNumber" placeholder="Número" class="flex-1" required>
                        <input type="text" id="shippingComplement" placeholder="Comp. (opcional)" class="flex-1">
                    </div>
                    <input type="text" id="shippingNeighborhood" placeholder="Bairro" required>
                    <div class="flex-row">
                        <input type="text" id="shippingCity" placeholder="Cidade" class="flex-2" required>
                        <input type="text" id="shippingState" placeholder="UF" class="flex-1" required maxlength="2" style="text-transform: uppercase;">
                    </div>
                </div>

                <div class="checkout-benefits">
                    <div class="checkout-benefits-title">✅ Ao finalizar, você garante:</div>
                    <ul>
                        <li>Aprovação imediata do pagamento</li>
                        <li>Frete grátis para todo Brasil</li>
                        <li>Garantia de 7 dias</li>
                    </ul>
                </div>

                <div class="checkout-testimonial">
                    <div class="stars">⭐⭐⭐⭐⭐</div>
                    <div class="text">"Chegou super rápido e ficou lindo na parede! A cascata de luzes é simplesmente mágica. Recomendo demais!"</div>
                    <div class="author">— Ana C., Belo Horizonte</div>
                </div>

                <button type="submit" class="buy-button" style="margin-top: 10px;">GERAR PIX AGORA</button>
            </form>
        </div>

        <div id="step2" style="display:none; text-align:center;">
            <h3>Pagamento via PIX</h3>
            <p>Escaneie o QR Code ou copie o código abaixo.</p>
            
            <div class="reserva-alert">
                <div class="icon">⚠️</div>
                <div class="text">ATENÇÃO: Conclua o pagamento <strong>antes de o código PIX expirar</strong>.</div>
            </div>
            
            <div class="timer-container">
                <div class="timer-label">⏱ Este código expira em:</div>
                <div id="pixTimer" class="timer">20:00</div>
            </div>
            
            <div class="qr-code-wrapper">
                <img decoding="async" id="qrCodeImage" src="" alt="QR Code PIX" style="display:none;" ondragstart="return false;">
            </div>
            
            
            
            <div style="background: #f0fdf4; border: 2px dashed #22c55e; border-radius: 8px; padding: 15px; margin-top: 15px;">
                <p style="font-size: 12px; color: #166534; font-weight: bold; margin-bottom: 8px;">👇 Toque no botão abaixo para copiar 👇</p>
                <button onclick="copyPixCode()" id="btnCopyPix" class="btn-copy-pix">
                    <svg width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 5H6a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2v-1M8 5a2 2 0 002 2h2a2 2 0 002-2M8 5a2 2 0 012-2h2a2 2 0 012 2m0 0h2a2 2 0 012 2v3m2 4H10m0 0l3-3m-3 3l3 3"></path></svg>
                    COPIAR CÓDIGO PIX
                </button>
                <input type="text" id="pixCopyPaste" readonly style="position: absolute; left: -9999px;">
            </div>
            
            <div class="guia-facil-pix">
                <h4>📝 Como pagar em 3 passos simples:</h4>
                <div class="guia-passo">
                    <div class="guia-numero">1</div>
                    <p class="guia-texto">Toque no botão verde <strong>"COPIAR CÓDIGO PIX"</strong> logo acima.</p>
                </div>
                <div class="guia-passo">
                    <div class="guia-numero">2</div>
                    <p class="guia-texto">Abra o aplicativo do seu banco, escolha a opção <strong>"PIX"</strong> e depois <strong>"Pix Copia e Cola"</strong>.</p>
                </div>
                <div class="guia-passo">
                    <div class="guia-numero">3</div>
                    <p class="guia-texto">Cole o código e confirme o pagamento.</p>
                </div>
                <div class="alerta-banco"><strong>Confira antes de pagar</strong>Confira o valor e o beneficiário no aplicativo do banco. Se aparecer um alerta de segurança, interrompa o pagamento e verifique a compra.</div>
            </div>
            
            <div class="security-banner"><strong>🔒 Pagamento via PIX</strong>Confirme os dados do destinatário no seu banco antes de pagar.</div>
            
            <div class="checkout-social-proof">
                <div class="stars">⭐⭐⭐⭐⭐</div>
                <div class="text">"Gerei o PIX, paguei na hora e em menos de 2 minutos já recebi o e-mail de confirmação. Super rápido!"</div>
                <div class="author">— Juliana M., São Paulo </div>
            </div>
            
            <p class="checking-payment" id="checkingPayment">Aguardando confirmação do pagamento...</p>
            <button class="buy-button" onclick="closeModal()" style="margin-top:15px; background:#555; box-shadow:0 4px 0 #333;">FECHAR</button>
        </div>

        <div id="successStep" style="display:none;">
            <div class="success-screen">
                <div class="success-icon">✓</div>
                <h3>Pagamento Confirmado!</h3>
                <p>Seu pedido foi aprovado com sucesso.<br>Guarde o número do seu pedido para acompanhar a compra.</p>
                <div class="success-details">
                    <strong>📦 Próximos passos:</strong>
                    • Envio em até 2 dias úteis<br>
                    • Prazo de entrega: 6 a 11 dias úteis<br>
                    • Você receberá o código de rastreio por e-mail
                </div>
                <button class="buy-button" onclick="closeModal()" style="margin-top:15px;">CONTINUAR</button>
            </div>
        </div>

        <div id="loadingStep" class="modal-loading" style="display:none;">
            <p>Gerando seu PIX com segurança...</p>
            <p style="font-size: 12px; font-weight: 400; color: #666; margin-top: 8px;">Aguarde um instante</p>
        </div>
    </div>
</div>



				</div>
					</div>
				</div>
				</div>
		<script>
window.cascataPrices = <?= json_encode($config['prices']) ?>;
window.cascataPixelId = <?= json_encode($config['facebook_pixel_id']) ?>;
!function(f,b,e,v,n,t,s){if(f.fbq)return;n=f.fbq=function(){n.callMethod?n.callMethod.apply(n,arguments):n.queue.push(arguments)};if(!f._fbq)f._fbq=n;n.push=n;n.loaded=!0;n.version='2.0';n.queue=[];t=b.createElement(e);t.async=!0;t.src=v;s=b.getElementsByTagName(e)[0];s.parentNode.insertBefore(t,s)}(window,document,'script','https://connect.facebook.net/en_US/fbevents.js');
if (/^\d+$/.test(window.cascataPixelId)) { fbq('init',window.cascataPixelId);fbq('track','PageView'); }
</script><script src="assets/qrcode.js"></script><script src="assets/app.js"></script></body></html>