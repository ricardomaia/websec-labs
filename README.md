# WebSec Labs

Ambiente para treinamento sobre vulnerabilidades em aplicações web.

![image](https://user-images.githubusercontent.com/1353811/202922879-3bd0619e-2ccf-4f53-a2f4-5e10af7c2991.png)

⚠️ **Atenção!**

**As configurações de ambiente e código-fonte são intencionalmente vulneráveis e não devem ser expostas à Internet ou em ambientes de produção.**

## Requisitos

- Git
- Docker Engine: <https://docs.docker.com/engine/install/ubuntu/> (Linux) <https://docs.docker.com/desktop/install/windows-install/> (Windows)
- Docker Compose: <https://docs.docker.com/compose/install/> (Linux)

## Etapas

 01. Clone este projeto.

        ```console
        git clone https://github.com/ricardomaia/websec-labs.git
        ```

 02. Construa a imagem do container.

        Windows

        ```console
        docker compose build
        ```

        Linux

        ```console
        docker-compose build
        ```

 03. Inicialize o container.

        Windows

        ```console
        docker compose up -d
        ```

        Linux

        ```console
        docker-compose up -d
        ```

 04. Acesse a aplicação

Abra o navegador em <http://localhost/>
