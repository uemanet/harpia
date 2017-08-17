FORMAT: 1A
HOST: http://localhost/moodle

# Integração

Plugin de integração entre Matraca e o Moodle

# Group Integracao

Usado para testar a conectividade com o plugin

## Ping [/webservice/rest/server.php?wstoken={wstoken}&wsfunction=integracao_ping&moodlewsrestformat=json]

### Ping [GET]
+ Request
    + Attributes
        + Body 
          {
            
          }

+ Response 200
    + Attributes (PingResponse)

+ Response 400
    + Attributes (PingError)

## Mudar papel de estudante [/webservice/rest/server.php?wstoken={wstoken}&wsfunction=local_integracao_change_role_student_course&moodlewsrestformat=json]

### Mudar papel de estudante [POST]
+ Request
    + Attributes (ChangeUserRoleRequest)

+ Response 200
    + Attributes (ChangeUserRoleResponse)

+ Response 400
    + Attributes (MoodleError)

## Mudar grupo de estudante [/webservice/rest/server.php?wstoken={wstoken}&wsfunction=local_integracao_change_student_group&moodlewsrestformat=json]

### Mudar grupo de estudante [POST]
+ Request
    + Attributes (ChangeUserGroupRequest)

+ Response 200
    + Attributes (ChangeUserGroupResponse)

+ Response 400
    + Attributes (MoodleError)


# Group Sandbox

Usado para testar a conectividade com o plugin

## Test post [/webservice/rest/server.php?wstoken={wstoken}&wsfunction=test_post&moodlewsrestformat=json]

### Test post [POST]
+ Request
    + Attributes
        + Body 
          {
            
          }

+ Response 200
    + Attributes (PingResponse)

+ Response 400
    + Attributes (PingError)


# Data Structures

## PingRequest (object)
+ Parameters
   + wstoken: asjowie54sre77adrf45as (string) - Token do plugin

## PingResponse (object)
+ Body 
  {
    response: true (boolean)
  }

## PingError (object)
+ exception: moodle_exception (string)
+ errorcode: invalidtoken (string)
+ message: Token inválido - token não encontrado (string)

## ChangeUserRoleRequest (object)
+ Parameters
   + wstoken: asjowie54sre77adrf45as (string) - Token do plugin
+ Body
   + trm_id: 1 (number)  - ID da Turma no Harpia
   + pes_id: 1 (number)  - ID da Pessoa no Harpia
   + new_status: trancado (string)  - Novo status de matrícula

## ChangeUserRoleResponse (object)
+ Body
   + id: 1 (number)  - ID do registro da operação
   + status: 2 (number)  - Status da operação
   + message: sucesso (string)  - Mensagem do Moodle

## MoodleError (object)
+ exception: moodle_exception (string)
+ errorcode: invalidtoken (string)
+ message: Valor inválido de parâmetro detectado (string)

## ChangeUserGroupRequest (object)
+ Parameters
   + wstoken: asjowie54sre77adrf45as (string) - Token do plugin
+ Body
   + mat_id: 1 (number)  - ID da Matrícula no Harpia
   + pes_id: 1 (number)  - ID da Pessoa no Harpia
   + old_grp_id: 1 (number)  - ID do grupo atual no Harpia (null por default)
   + new_grp_id: 1 (number)  - ID do novo grupo no Harpia

## ChangeUserGroupResponse (object)
+ Body
   + id: 1 (number)  - ID do registro da operação
   + status: 2 (number)  - Status da operação
   + message: sucesso (string)  - Mensagem do Moodle
