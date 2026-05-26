# 🎤 Karaokê - Sistema de Gerenciamento de Músicas

Um sistema web completo e moderno para gerenciamento e exibição de músicas de karaokê, com telão interativo, listagem responsiva, favoritos e busca dinâmica.

## 🌟 Características

- **Telão Interativo** - Exibição grande e clara de código, música e artista (ideal para TVs e projetores)
- **YouTube Integration** - Link direto para buscar a música no YouTube com cache em banco
- **Biografia do Artista** - Link para Wikipedia com informações completas
- **Busca por Artista** - Listagem de todos os artistas com busca dinâmica em tempo real
- **Favoritos** - Sistema de favoritos usando cookies, sincronizável entre dispositivos
- **Listagem Completa** - Tabela com paginação, ordenada e com cores intercaladas
- **Busca Avançada** - Filtros por categoria, gênero, idioma e artista
- **CSS Centralizado** - Arquitetura modular com estilos consistentes
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
    artista_normalizado VARCHAR(255),
    categoria VARCHAR(50),
    idioma VARCHAR(50),
    genero VARCHAR(50),
    youtube_id VARCHAR(20),
    youtube_titulo VARCHAR(255),
    youtube_thumbnail VARCHAR(500),
    youtube_canal VARCHAR(255),
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

### Buscar por Artista (com filtro dinâmico)
```
http://localhost/artista.php
```
Digite o nome do artista para filtrar em tempo real.

### Ver Músicas de um Artista Específico
```
http://localhost/buscar_artista.php?art=nome_do_artista
```

### Busca Global
```
http://localhost/buscar.php?q=nome_da_musica_ou_artista
```

### Buscar por Categoria
```
http://localhost/buscar_categoria.php?cat=pop
```

### Buscar por Gênero
```
http://localhost/buscar_genero.php?gen=rock
```

### Buscar por Idioma
```
http://localhost/buscar_idioma.php?idioma=português
```

### Ver Favoritos
```
http://localhost/favoritos.php
```
Clique no coração em qualquer música para adicionar aos favoritos (armazenados localmente).


## 📁 Estrutura de Arquivos

```
karaoke/
├── README.md                  # Este arquivo
├── styles.css                 # Estilos CSS centralizados
├── index.php                  # Página inicial
├── telao.php                  # Tela grande para karaokê
├── todas.php                  # Listagem completa de músicas
├── conexao.php                # Configuração do banco de dados
├── favorites.js               # Sistema de favoritos (JavaScript)
├── favoritos.php              # Página de favoritos
├── buscar.php                 # Busca global
├── artista.php                # Listagem de artistas com filtro
├── buscar_artista.php         # Busca de músicas por artista
├── buscar_categoria.php       # Busca por categoria
├── buscar_genero.php          # Busca por gênero
├── buscar_idioma.php          # Busca por idioma
├── categoria.php              # Listagem de categorias
├── genero.php                 # Listagem de gêneros
├── idioma.php                 # Listagem de idiomas
├── youtube_helper.php         # Integração com YouTube API
├── layout_topo.php            # Template do cabeçalho e menu
├── layout_rodape.php          # Template do rodapé
└── dvdrental/                 # Arquivos adicionais
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

### Listagem Geral (todas.php)
- Tabela com 3 colunas: **Código | Artista | Música**
- Paginação (10, 20 ou 50 itens por página)
- Cores intercaladas para fácil leitura
- Botões de favorito em cada linha
- Clicável - abre automaticamente no telão
- Responsivo para tablets e celulares

### Busca de Artista (artista.php)
- **Filtro dinâmico em tempo real** - digita e a lista é filtrada instantaneamente
- Cores intercaladas (zebra striping) para melhor legibilidade
- Mostra quantidade de artistas encontrados
- Layout compacto para visualizar muitos artistas
- Clique em um artista para ver todas suas músicas

### Menu de Navegação (layout_topo.php)
- Links para: Início, Artista, Categoria, Gênero, Idioma, Todas, Favoritos
- Design responsivo com ícones intuitivos
- Aparece em todas as páginas

### Sistema de Favoritos
- Ícone de coração em cada música
- Armazenamento em cookie (persistente por 365 dias)
- Página dedicada para visualizar favoritos
- Sincronização com banco de dados opcional

## 📱 Responsividade

- **Desktop** (1024px+): Layout completo com fonte grande
- **Tablet** (768px-1023px): Layout ajustado, fonte 20px
- **Mobile** (< 480px): Layout otimizado, fonte 16px no artista

## 🔍 Recursos de Busca

1. **Busca Global** (buscar.php) - Procura por artista ou música
2. **Busca por Artista** (artista.php) - Lista todos os artistas com filtro dinâmico em tempo real
3. **Músicas do Artista** (buscar_artista.php) - Todas as músicas de um artista específico
4. **Por Categoria** (buscar_categoria.php) - Filtra por categoria de música
5. **Por Gênero** (buscar_genero.php) - Filtra por gênero (rock, pop, etc)
6. **Por Idioma** (buscar_idioma.php) - Filtra por idioma (português, inglês, etc)
7. **Favoritos** (favoritos.php) - Músicas marcadas como favoritas pelo usuário

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

**Última atualização:** 25 de Maio de 2026  
**Versão:** 2.0  
**Status:** Ativo ✅

## 📋 Histórico de Versões

### v2.0 (25/05/2026)
- ✨ CSS centralizado em `styles.css`
- ✨ Nova página de busca por artista com filtro dinâmico
- ✨ Cores intercaladas (zebra striping) nas listas
- ✨ Sistema de favoritos com cookies
- 🔧 Melhoria no layout responsivo
- 🔧 Compactação de elementos para melhor visualização
- 📝 Suporte a campo `artista_normalizado` para buscas normalizadas
- 📝 Campos adicionais no banco para cache de YouTube

### v1.0 (23/05/2026)
- Versão inicial do sistema
- Telão interativo
- Busca por categoria, gênero e idioma
- Integração com YouTube
- Links para Wikipedia

Divirta-se com o karaokê! 🎤🎵
