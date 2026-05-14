# 🎤 Karaokê - Sistema de Gerenciamento de Músicas

Um sistema web completo para gerenciamento e exibição de músicas de karaokê, com telão interativo e listagem responsiva.

## 🌟 Características

- **Telão Interativo** - Exibição grande e clara de código, música e artista (ideal para TVs e projetores)
- **YouTube Integration** - Link direto para buscar a música no YouTube
- **Biografia do Artista** - Link para Wikipedia com informações completas
- **Busca de Artista** - Busca rápida de todas as músicas de um artista
- **Listagem Completa** - Tabela com 20 itens por página, ordenada por artista e música
- **Responsivo** - Funciona perfeitamente em desktop, tablet e celular
- **SEO Otimizado** - Meta tags para melhor indexação

## 🎯 Requisitos

- **PHP** 7.4+
- **PostgreSQL** 10+
- **Servidor Web** (Apache, Nginx, etc)
- **Browser Moderno** (Chrome, Firefox, Safari, Edge)

## 📦 Instalação

### 1. Clone o Repositório
```bash
git clone https://github.com/klausKellerbra/karaoke.git
cd karaoke
```

### 2. Configure o Banco de Dados

Crie um banco PostgreSQL com a tabela de músicas:

```sql
CREATE DATABASE KARAOKE;

CREATE TABLE musicas_karaoke (
    id SERIAL PRIMARY KEY,
    codigo VARCHAR(10) UNIQUE NOT NULL,
    musica VARCHAR(255) NOT NULL,
    artista VARCHAR(255) NOT NULL,
    idioma VARCHAR(50),
    genero VARCHAR(50),
    data_adicao TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);
```

### 3. Atualize a Conexão

Edite o arquivo `conexao.php` com suas credenciais PostgreSQL:

```php
$conn = pg_connect("
    host=localhost
    dbname=KARAOKE
    user=seu_usuario
    password=sua_senha
");
```

### 4. Configure o Servidor Web

Coloque os arquivos em seu diretório web (ex: `/var/www/html`):

```bash
cp -r karaoke/* /var/www/html/
```

## 🚀 Como Usar

### Acessar a Página Principal
```
http://localhost/index.php
```

### Exibir uma Música no Telão
```
http://localhost/telao.php?codigo=0001
```

Ou clique em qualquer música na listagem que abre automaticamente no telão.

### Ver Todas as Músicas
```
http://localhost/todas.php
```

### Buscar por Artista
```
http://localhost/buscar.php?q=nome_do_artista
```

### Buscar por Categoria
```
http://localhost/buscar_categoria.php?categoria=pop
```

### Buscar por Gênero
```
http://localhost/buscar_genero.php?genero=rock
```

### Buscar por Idioma
```
http://localhost/buscar_idioma.php?idioma=português
```

## 📁 Estrutura de Arquivos

```
karaoke/
├── README.md                  # Este arquivo
├── index.php                  # Página inicial
├── telao.php                  # Tela grande para karaokê
├── todas.php                  # Listagem completa de músicas
├── conexao.php                # Configuração do banco de dados
├── buscar.php                 # Busca global
├── buscar_categoria.php       # Busca por categoria
├── buscar_genero.php          # Busca por gênero
├── buscar_idioma.php          # Busca por idioma
├── categoria.php              # Listagem de categorias
├── genero.php                 # Listagem de gêneros
├── idioma.php                 # Listagem de idiomas
├── layout_topo.php            # Template do cabeçalho
├── layout_rodape.php          # Template do rodapé
└── dvdrental/                 # Arquivos de integração
```

## 🎨 Interface

### Telão (telao.php)
- Exibição grande e legível para TV ou projetor
- Código da música em **150px** (desktop)
- Música em **40px**
- Artista em **30px** com link interativo
- Botão para buscar no YouTube
- Botão para ver biografia no Wikipedia
- Totalmente responsivo para mobile

### Listagem (todas.php)
- Tabela com 3 colunas: **Código | Artista | Música**
- 20 itens por página com paginação
- Cores destacadas para fácil leitura
- Clicável - abre automaticamente no telão
- Responsivo para tablets e celulares

## 📱 Responsividade

- **Desktop** (1024px+): Layout completo com fonte grande
- **Tablet** (768px-1023px): Layout ajustado, fonte 20px
- **Mobile** (< 480px): Layout otimizado, fonte 16px no artista

## 🔍 Recursos de Busca

1. **Busca Global** - Procura por artista ou música
2. **Por Categoria** - Filter por categoria de música
3. **Por Gênero** - Filter por gênero (rock, pop, etc)
4. **Por Idioma** - Filter por idioma (português, inglês, etc)

## 🎯 Funcionalidades Principais

### Telão (telao.php)
```
┌─────────────────────────┐
│                         │
│        [CÓDIGO]         │
│                         │
│      [MÚSICA]           │
│                         │
│  [ARTISTA]              │
│ 📖 Biografia | ▶️ YouTube│
│                         │
└─────────────────────────┘
```

### Listagem (todas.php)
```
┌──────┬──────────┬──────────┐
│ Código│ Artista  │ Música   │
├──────┼──────────┼──────────┤
│ 0001 │ The Beatles│ Yesterday│
│ 0002 │ Queen    │ Bohemian │
│ 0003 │ Pink Floyd│ Comfortably│
└──────┴──────────┴──────────┘
```

## 🔐 Segurança

- Uso de `pg_query_params()` para previnir SQL Injection
- `htmlspecialchars()` para previnir XSS
- `urlencode()` para URLs seguras
- Tratamento de erros

## 🛠️ Manutenção

### Adicionar Nova Música

```sql
INSERT INTO musicas_karaoke (codigo, musica, artista, idioma, genero)
VALUES ('0100', 'Imagine', 'John Lennon', 'inglês', 'rock');
```

### Atualizar Música

```sql
UPDATE musicas_karaoke 
SET musica = 'Novo Nome' 
WHERE codigo = '0001';
```

### Deletar Música

```sql
DELETE FROM musicas_karaoke 
WHERE codigo = '0001';
```

## 📊 Consultas SQL Úteis

### Total de Músicas
```sql
SELECT COUNT(*) FROM musicas_karaoke;
```

### Músicas por Artista
```sql
SELECT COUNT(*) FROM musicas_karaoke 
WHERE artista = 'The Beatles';
```

### Músicas por Gênero
```sql
SELECT COUNT(*) FROM musicas_karaoke 
WHERE genero = 'rock';
```

### Artistas com Mais Músicas
```sql
SELECT artista, COUNT(*) as total 
FROM musicas_karaoke 
GROUP BY artista 
ORDER BY total DESC 
LIMIT 10;
```

## 🎬 Screenshots

### Telão Desktop
- Exibição grande e clara
- Perfeito para TV 55" ou maior
- Código em destaque
- Links interativos

### Listagem Mobile
- Tabela responsiva
- Columns ajustadas
- Fácil de navegar
- Touch-friendly

## 🐛 Troubleshooting

### "Código não fornecido"
- Acesse telao.php com um parâmetro código válido
- Exemplo: `telao.php?codigo=0001`

### "Erro ao buscar música"
- Verifique a conexão com o PostgreSQL
- Cheque o arquivo `conexao.php`
- Confirme que a tabela existe

### Página em branco
- Ative o erro do PHP em `php.ini`
- Verifique os logs do servidor
- Confirme que o PostgreSQL está rodando

## 📞 Suporte

Para dúvidas ou problemas, abra uma [issue](https://github.com/klausKellerbra/karaoke/issues) no GitHub.

## 📄 Licença

Este projeto é de código aberto e está disponível sob a [MIT License](LICENSE).

## 👨‍💻 Autor

**Klaus Kellerbra**  
GitHub: [@klausKellerbra](https://github.com/klausKellerbra)

## 🙏 Agradecimentos

- PostgreSQL pela robustez
- PHP pela simplicidade
- GitHub pela hospedagem
- Comunidade open-source

---

**Última atualização:** Maio 2026  
**Versão:** 1.0  
**Status:** Ativo ✅

Divirta-se com o karaokê! 🎤🎵
