=== SC Events ===
Contributors: Pedro Matias
Tags: events, event management, shortcode, custom post type, calendar, agenda
Requires at least: 5.8
Tested up to: 6.5
Stable tag: 2.0.0
License: GPLv2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.txt

Um plugin simples e flexível para criar, gerir e exibir eventos usando um shortcode e uma página de arquivo dedicada.

== Description ==

O SC Events oferece uma maneira simples de criar e exibir eventos num site WordPress. 
O SC Events foi projetado para ser leve e fácil de usar, permitindo um controlo sobre multiplos eventos sem complexidade desnecessária.


PLUGIN SC EVENTS: GUIA DO UTILIZADOR
====================================


ÍNDICE
------

1. Instalação e Primeiros Passos
2. Criar e Gerir Eventos
3. Exibir Eventos com Shortcodes
   - Utilização Básica
   - Atributos do Shortcode
4. Personalizar Estilos com CSS
5. Integração com Page Builders (Avada & Elementor)
   - Como Usar com o Avada
   - Como Usar com o Elementor


1. INSTALAÇÃO E PRIMEIROS PASSOS
---------------------------------

Antes de começar, certifique-se de que tem o ficheiro `sc-events.zip`.
Pode obter o ficheiro em "https://github.com/scml-uacor-nwc/sc-events"

Para Instalar o Plugin:
1. No seu Painel do WordPress, navegue até Plugins > Adicionar Novo.
2. Clique no botão "Carregar plugin" no topo da página.
3. Escolha o ficheiro `sc-events.zip` do seu computador e clique em "Instalar agora".
4. Após a instalação, clique em "Ativar Plugin".

*** IMPORTANTE ***
Depois de ativar o plugin, tem de atualizar as ligações permanentes do seu site.
1. Vá a Definições > Ligações Permanentes.
2. Não precisa de alterar nenhuma definição. Apenas clique no botão "Guardar Alterações".
3. Este passo garante que a sua página principal de eventos em `oseusite.com/events` irá funcionar corretamente.


2. CRIAR E GERIR EVENTOS
-------------------------

O plugin adiciona um novo menu "Events" ao seu painel de administração do WordPress.

Para Criar um Novo Evento:
1. Navegue até Events > Add New (Adicionar Novo).
2. Título: Adicione um título para o seu evento.
3. Introdução/Descrição: 
    - Use o editor de conteúdo principal para escrever sobre o seu evento. 
    - A primeira parte deste texto será usada para o efeito de hover nos cartões de evento.
4. Caixa "Event Details" (Detalhes do Evento): Abaixo do editor principal, encontrará uma caixa com os seguintes campos:
   - Start Date and Hour (Data e Hora de Início): A hora exata em que o evento começa. Este campo é OBRIGATÓRIO.
   - End Date and Hour (Data e Hora de Fim): A hora em que o evento termina. Pode deixar em branco para eventos de um só dia.
   - Place (Local): A localização do evento (ex: "Sala de Extrações", "Teams", "Online" ou "https://youtube/o_meu_evento").
   - Registry (URL) (Registo): O link completo para uma página de registo (ex: `https://exemplo.com/registo`).
   - Contacts (Contactos): Qualquer informação de contacto, como um número de telefone ou email.
5. Event Categories (Categorias de Eventos) (Barra lateral direita): 
    - Organize os seus eventos atribuindo-os a categorias. 
    - Pode criar novas categorias diretamente aqui, clicando em "Adicionar Nova Categoria de Evento".
6. Featured Image (Imagem Destacada) (Barra lateral direita): Adicione uma imagem principal para o seu evento. Esta imagem aparecerá no topo da página de detalhe do evento.
7. Clique em "Publicar" para guardar o seu evento.


3. EXIBIR EVENTOS COM SHORTCODES
---------------------------------

A funcionalidade mais poderosa deste plugin é o shortcode `[sc_events]`, que lhe permite colocar uma grelha de cartões de eventos em qualquer parte do seu site.

Utilização Básica:
Para exibir uma grelha padrão com os próximos 3 eventos, basta adicionar um Bloco de Shortcode (no Gutenberg) ou um Bloco de Texto/Código (num page builder) e insira o seguinte:
[sc_events]

Atributos do Shortcode:
Pode personalizar o shortcode adicionando "atributos" para controlar o layout e o que é exibido.

- limit
  O que faz: Controla o número máximo de eventos a serem exibidos.
  Exemplo: Para mostrar os próximos 6 eventos:
  [sc_events limit="6"]

- columns
  O que faz: Define o número de colunas para a grelha em ecrãs de computador (é sempre 1 coluna em dispositivos móveis).
  Opções: 1, 2, ou 3.
  Exemplo: Para exibir eventos numa grelha de 2 colunas:
  [sc_events columns="2"]

- category
  O que faz: Filtra a exibição para mostrar apenas eventos de uma categoria específica.
  Como encontrar o slug: Vá a Events > Categories (Categorias). 
  O "slug" é o nome da categoria formatado para URL.
  Exemplo: Para mostrar apenas eventos da categoria com o slug "workshops":
  [sc_events category="workshops"]

- excerpt_length
  O que faz: Controla o número de caracteres exibidos no texto do pop-up ao passar o rato.
  Exemplo: Para mostrar um excerto mais longo com 120 caracteres:
  [sc_events excerpt_length="120"]

- hover
  O que faz: Ativa ou desativa o efeito de pop-up ao passar o rato sobre os cartões de evento.
  Opções: true ou false.
  Exemplo: Para exibir cartões estáticos sem efeito de hover:
  [sc_events hover="false"]

Combinar Atributos:
Pode misturar e combinar atributos para criar a exibição perfeita.
Exemplo: Mostrar os próximos 4 eventos da categoria "webinars" numa grelha de 2 colunas e sem efeito de hover.
[sc_events limit="4" columns="2" category="webinars" hover="false"]


4. PERSONALIZAR ESTILOS COM CSS
--------------------------------

Se quiser fazer pequenos ajustes de estilo (como alterar cores ou tamanhos de fonte) para corresponder ao seu tema, pode facilmente adicionar as suas próprias regras de CSS.

1. Navegue até Events > Custom CSS.
2. Insira as suas regras de CSS na caixa de texto.
3. Clique em "Guardar Alterações".

Exemplo: Para alterar a cor da caixa preta da data para azul, pode adicionar:
.sc-events-card__date { background-color: #0073aa; }


5. INTEGRAÇÃO COM PAGE BUILDERS (AVADA & ELEMENTOR)
------------------------------------------------------

Este plugin foi construído para ser compatível com page builders. A chave é usar os shortcodes.

Como Usar com o Avada (Fusion Builder):

- Para Exibir a Grelha de Cartões de Evento:
  1. Edite uma página ou artigo com o Fusion Builder.
  2. Adicione um elemento "Code Block" (Bloco de Código) ao seu layout.
  3. Dentro do Bloco de Código, cole o seu shortcode `[sc_events]` desejado (ex: `[sc_events limit="6" columns="3"]`).
  4. Guarde a página.

- Para Configurar a Página de Detalhe de um Evento:
  O Avada controla o layout de todos os tipos de posts, por isso precisamos de lhe dizer como exibir os nossos eventos.
  1. No seu Painel do WordPress, vá a Avada > Layouts.
  2. Clique em "Add New" para criar um novo Layout. Dê-lhe um nome como "Template Evento Individual".
  3. Na secção "Layout Conditions", defina a condição para "Events" e depois "All Events". 
     (Isto diz ao Avada para usar este layout para cada evento individual.)
  4. Clique em "Create a Custom Layout". Adicione uma Secção e uma Coluna.
  5. Dentro da coluna, adicione um elemento "Code Block".
  6. Dentro do Bloco de Código, cole este shortcode específico: [sc_event_details]
  7. Publique o layout. Agora, todas as suas páginas de eventos individuais usarão este template e serão exibidas corretamente.

  A Nota Importante Sobre Estilos no Avada
-------------------------------------------
    O Avada tem um sistema de estilos muito poderoso que controla a aparência de todo o conteúdo do seu site, incluindo os títulos (H1, H2, etc.) e os parágrafos.

    Depois de inserir o shortcode do evento, poderá notar que o espaçamento ou o tamanho do texto não parece perfeito. Isto é normal e esperado. O Avada está a aplicar os seus próprios estilos globais ao HTML do nosso plugin.

    A forma correta de ajustar a aparência é usar as ferramentas do próprio Avada:

    1.  **Ajustar o Espaçamento:**
        - Edite a sua página com o Fusion Builder.
        - Selecione o "Elemento" que contém o seu shortcode `[sc_events]` (provavelmente um Bloco de Código).
        - Vá ao separador "Design".
        - Use as opções de "Margin" (margem) para adicionar espaço acima ou abaixo do bloco de eventos, para que não se sobreponha a outros elementos.

    2.  **Ajustar os Estilos do Texto do Evento:**
        - Se os títulos dos eventos parecerem demasiado grandes, pode usar o painel de "Custom CSS" do nosso plugin para os ajustar.
        - Vá a Events > Custom CSS e adicione uma regra mais específica. Por exemplo:
        
        /* Força um tamanho de fonte mais pequeno para os títulos dos cartões de evento */
        .sc-events-card__title {
            font-size: 18px !important;
            line-height: 1.2 !important;
        }

        /* Força um tamanho de fonte mais pequeno para o excerto */
        .sc-events-card__excerpt {
            font-size: 14px !important;
        }

    Usar `!important` não é normalmente a melhor prática, mas é muitas vezes necessário para substituir os estilos fortes de temas complexos como o Avada.

    Ao usar as ferramentas do Avada para o espaçamento e o painel de CSS do plugin para ajustes finos, garante que a aparência final fica perfeitamente integrada com o resto do seu site.

    == AVADA == In English for easy of use & understanding
        In your WordPress Dashboard, go to Avada > Layouts.
        Click "Add New" to create a new layout. Give it a name like "Single Event Layout".
        In the Layout Conditions, set it to display on "Events" > "All Events". This tells Avada to use this layout for every single event post.
        Design your layout. You will likely just have a single section with one column.
        Inside that column, add a "Code Block" element (or a "Text Block" element).
        Inside the element, type the single shortcode: [sc_event_details]
        Publish the layout.

Como Usar com o Elementor:

- Para Exibir a Grelha de Cartões de Evento:
  1. Edite uma página ou artigo com o Elementor.
  2. No painel de widgets à esquerda, procure pelo widget "Shortcode".
  3. Arraste o widget Shortcode para o seu layout.
  4. No painel de configurações do widget, cole o seu shortcode `[sc_events]` desejado.
  5. Guarde a página.

- Para Configurar a Página de Detalhe de um Evento:
  O Elementor Pro permite-lhe criar templates para tipos de post personalizados.
  1. No seu Painel do WordPress, vá a Modelos > Construtor de Temas (Theme Builder).
  2. Vá ao separador "Artigo Individual" (Single Post) e clique em "Adicionar Novo".
  3. Quando lhe for pedido um Tipo de Post, selecione "Event". Dê um nome ao seu modelo (ex: "Template Evento Individual").
  4. Nas Condições de Exibição, certifique-se de que está definido para "Events" > "All".
  5. Desenhe a sua página. Arraste um widget "Shortcode" para a área de conteúdo principal.
  6. Nas configurações do widget, cole este shortcode específico: [sc_event_details]
  7. Publique o modelo. Agora, todas as suas páginas de eventos individuais usarão este modelo do Elementor.