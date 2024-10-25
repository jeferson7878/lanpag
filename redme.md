## Requisitos Básicos:
1. **Sistema Operacional**:
   - Para **Windows**: Windows 10 ou superior.
   - Para **Linux**: Distribuição baseada em Ubuntu ou Debian (recomendado).
2. **Conexão com a Internet**: Necessário para baixar os arquivos.
3. **Permissões de Administrador**: Para realizar a instalação corretamente.

## Passo a Passo para Windows:

### 1. **Download e Instalação do Laragon**
- Acesse o site oficial do [Laragon](https://laragon.org/download/index.html).
- Baixe a versão "Laragon Full" (recomendada para iniciantes).
- Depois do download, execute o instalador (`.exe`).
- Siga as instruções de instalação (clique em "Next" e selecione a pasta de instalação, geralmente `C:\Laragon`).
- No final, marque a opção "Start Laragon" para iniciar o programa.

### 2. **Configuração do Ambiente PHP e MySQL no Laragon**
- Quando abrir o Laragon, você verá opções como **Start All**, **Stop All**, e **Services**.
- Clique em **Start All** para iniciar o Apache e o MySQL.
- Para verificar se está tudo funcionando, clique em **Menu > www > Open**. Isso abrirá o navegador na página local (`localhost`), onde você verá a página inicial do Laragon.

### 3. **Configuração do Banco de Dados (MySQL)**:
- Acesse **Database > phpMyAdmin** no menu.
- Crie uma nova base de dados para o seu projeto PHP.

### 4. **Instalando e Usando o PHP 8.2**
- O Laragon permite que você mude a versão do PHP facilmente. Caso precise do PHP 8.2, vá para **Menu > PHP > Versão** e escolha a versão mais recente (se necessário, baixe a versão no site oficial do PHP).

## Passo a Passo para Linux:

### 1. **Instalando o Apache, MySQL e PHP (LAMP)**
No Linux, uma alternativa ao Laragon é instalar o stack LAMP (Linux, Apache, MySQL e PHP).

Execute os comandos a seguir no terminal para instalar:

```bash
sudo apt update
sudo apt install apache2 mysql-server php libapache2-mod-php php-mysql
```

### 2. **Instalando o PHP 8.2 no Linux**
Para garantir que você tenha o PHP 8.2, execute os seguintes comandos:

```bash
sudo apt install software-properties-common
sudo add-apt-repository ppa:ondrej/php
sudo apt update
sudo apt install php8.2 libapache2-mod-php8.2
```

### 3. **Configurando o Servidor**
- Verifique se o Apache está rodando:

```bash
sudo systemctl status apache2
```

- Coloque seus arquivos PHP no diretório `/var/www/html/` para que sejam acessíveis pelo navegador. Abra `localhost` no navegador para verificar se o servidor está funcionando.

### 4. **Configurando o MySQL**
- Acesse o MySQL para configurar o banco de dados:

```bash
sudo mysql
```
### 5. **Alternativa: WampServer**
Se você preferir uma solução mais simplificada e gráfica no Linux, pode usar o **WampServer**, que é uma excelente alternativa para desenvolvimento local. Ele oferece uma interface fácil para gerenciar Apache, MySQL e PHP.

- Baixe e instale o [WampServer](https://www.wampserver.com/en/), e siga as instruções da página oficial para iniciar seu ambiente.

## Problemas Comuns:
- **Erro 404 ao acessar o projeto**: Verifique se os serviços estão ativos e se o projeto está na pasta correta (`/var/www/html` ou `www`).
- **Falha na conexão com o banco de dados**: Verifique as configurações de conexão no arquivo `config.php`.
