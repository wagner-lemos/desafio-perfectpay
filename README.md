### Desafio DEV-PHP da PerfectPay

Esse desafio foi solicitado para a participação do processo seletivo da PerfectPay.<br/><br/>
<b>Objetivo:</b> O desafio é desenvolver uma aplicação para processamento de pagamentos integrado ao ambiente de homologação do Asaas, levando em consideração que o cliente deve acessar uma página onde irá selecionar a opção de pagamento entre Boleto, Cartão ou Pix.<br/><br/>

<b>OBS:</b> A aplicação foi desenvolvida em menos de 5 horas após o recebimento do briefing e da documentação pela recrutadora, para mostrar que alem de conhecimento, também posso mostrar agilidade com qualidade no desenvolvimento.

#### Instalação
```
• Versao do PHP: 8.1
• Versao do Laravel: 10.10
```
#### Clone o projeto
```
• git clone https://github.com/wagner-lemos/desafio-perfectpay.git
• Configure o .env com seus dados necessarios como acesso ao BD SQL. E sua ASAAS_KEY_API (Já incluir uma pre-configurada)
```
#### Instale as dependências
```
composer install
```
#### Execute a migrate e a seed para instalar e popular o banco
```
php artisan migrate
php artisan db:seed
```
#### E por fim, execute o servidor
```
php artisan serve
```