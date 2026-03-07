# **Guia de Migração: Laravel Collective para Spatie HTML (Laravel 8 ➡️ 12\)**

A transição do laravelcollective/html (abandonado e incompatível com versões recentes do Laravel) para o spatie/laravel-html é um passo essencial na atualização do seu projeto para o Laravel 12\.

A principal diferença arquitetónica é que o Laravel Collective usava *arrays* para definir atributos, enquanto a biblioteca da Spatie utiliza uma **interface fluente** (encadeamento de métodos), resultando num código mais limpo e moderno.

## **1\. Preparação do Ambiente**

Antes de atualizar o laravel/framework no seu composer.json, remova o pacote antigo para evitar conflitos de dependências:

\# 1\. Remover o pacote antigo  
composer remove laravelcollective/html

\# 2\. Instalar o novo pacote da Spatie  
composer require spatie/laravel-html

## **2\. Formulários: Abertura e Fecho**

### **Formulário Padrão**

**Antes (Collective):**

{{ Form::open(\['url' \=\> '/route', 'method' \=\> 'POST', 'class' \=\> 'form'\]) }}  
    ...  
{{ Form::close() }}

**Depois (Spatie):**

{{ html()-\>form('POST', '/route')-\>class('form')-\>open() }}  
    ...  
{{ html()-\>form()-\>close() }}

### **Formulário com Model Binding (Edição)**

**Antes (Collective):**

{{ Form::model($user, \['route' \=\> \['users.update', $user-\>id\], 'method' \=\> 'PUT'\]) }}  
    ...  
{{ Form::close() }}

**Depois (Spatie):**

{{ html()-\>modelForm($user, 'PUT', route('users.update', $user-\>id))-\>open() }}  
    ...  
{{ html()-\>closeModelForm() }}

*Nota: A Spatie preenche automaticamente os valores dos inputs baseando-se no modelo fornecido.*

### **Formulário com Upload de Ficheiros**

**Antes (Collective):**

{{ Form::open(\['url' \=\> '/upload', 'files' \=\> true\]) }}

**Depois (Spatie):**

{{ html()-\>form('POST', '/upload')-\>acceptsFiles()-\>open() }}

## **3\. Mapeamento de Elementos (De ➡️ Para)**

### **Labels**

* **Collective:** {{ Form::label('email', 'O seu E-mail', \['class' \=\> 'form-label'\]) }}  
* **Spatie:** {{ html()-\>label('O seu E-mail', 'email')-\>class('form-label') }}

### **Inputs de Texto**

* **Collective:** {{ Form::text('name', 'Valor', \['class' \=\> 'input', 'id' \=\> 'name\_id'\]) }}  
* **Spatie:** {{ html()-\>text('name', 'Valor')-\>class('input')-\>id('name\_id') }}

### **Email e Password**

* **Collective:** {{ Form::email('email', null, \['class' \=\> 'input'\]) }}  
  {{ Form::password('password', \['class' \=\> 'input'\]) }}  
* **Spatie:** {{ html()-\>email('email')-\>class('input') }}  
  {{ html()-\>password('password')-\>class('input') }}

### **Textarea**

* **Collective:** {{ Form::textarea('notes', 'Texto...', \['rows' \=\> 3\]) }}  
* **Spatie:** {{ html()-\>textarea('notes', 'Texto...')-\>attribute('rows', 3\) }}

### **Campos Ocultos (Hidden)**

* **Collective:** {{ Form::hidden('user\_id', $user-\>id) }}  
* **Spatie:** {{ html()-\>hidden('user\_id', $user-\>id) }}

### **Upload de Ficheiros (File)**

* **Collective:** {{ Form::file('avatar', \['class' \=\> 'file-input'\]) }}  
* **Spatie:** {{ html()-\>file('avatar')-\>class('file-input') }}

## **4\. Seleção e Opções**

### **Selects (Dropdowns)**

* **Collective:** {{ Form::select('status', \['A' \=\> 'Ativo', 'I' \=\> 'Inativo'\], 'A', \['class' \=\> 'select'\]) }}  
* **Spatie:** {{ html()-\>select('status', \['A' \=\> 'Ativo', 'I' \=\> 'Inativo'\], 'A')-\>class('select') }}

### **Checkboxes**

* **Collective:** {{ Form::checkbox('is\_admin', 1, true) }}  
* **Spatie:** {{ html()-\>checkbox('is\_admin', true, 1\) }}  
  *(Atenção: A ordem dos parâmetros inverteu. Na Spatie é nome, checked (booleano), valor)*

### **Radio Buttons**

* **Collective:** {{ Form::radio('gender', 'M', false) }}  
* **Spatie:** {{ html()-\>radio('gender', false, 'M') }}  
  *(Atenção: A ordem dos parâmetros também inverteu. Na Spatie é nome, checked, valor)*

## **5\. Botões**

### **Submit**

* **Collective:** {{ Form::submit('Guardar', \['class' \=\> 'btn btn-primary'\]) }}  
* **Spatie:** {{ html()-\>submit('Guardar')-\>class('btn btn-primary') }}

### **Button (Genérico)**

* **Collective:** {{ Form::button('Clique aqui', \['class' \=\> 'btn'\]) }}  
* **Spatie:** {{ html()-\>button('Clique aqui')-\>class('btn') }}

## **6\. Dicas de Automatização para a Refatoração**

Para projetos grandes, refatorar manualmente ficheiro a ficheiro pode ser exaustivo. Utilize o recurso de *Search & Replace* (Pesquisar e Substituir) da sua IDE (VS Code, PhpStorm) ativando a opção de Expressões Regulares (Regex):

**1\. Substituir o fecho padrão (Simples):**

* **Find:** \\{\\{\\s\*Form::close\\(\\)\\s\*\\}\\}  
* **Replace:** {{ html()-\>form()-\>close() }}

**2\. Substituir labels simples:**

* **Find:** \\{\\{\\s\*Form::label\\((\[^,\]+),\\s\*(\[^)\]+)\\)\\s\*\\}\\}  
* **Replace:** {{ html()-\>label($2, $1) }}

**3\. Substituir inputs de texto simples (sem atributos extra):**

* **Find:** \\{\\{\\s\*Form::text\\((\[^,\]+)\\)\\s\*\\}\\}  
* **Replace:** {{ html()-\>text($1) }}

*Aviso: As Expressões Regulares ajudam no trabalho em massa, mas verifique sempre os ficheiros alterados, especialmente nos inputs onde passava arrays complexos de classes CSS ou atributos de dados (data-attributes).*