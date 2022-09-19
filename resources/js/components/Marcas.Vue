<template>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <!-- Inicio card de busca -->
                <card-component titulo="Buscar marcas">
                    <template v-slot:conteudo>
                        <div class="form-row">
                            <div class="col mb-3">
                                <input-container-component titulo="ID" id="inputId" id-help="idHelp"
                                    texto-ajuda="Opicional. informe o id da marca">
                                    <input type="number" class="form-control" id="inputId" aria-describedby="idHelp"
                                        placeholder="ID">
                                </input-container-component>
                            </div>
                            <div class="col mb-3">
                                <input-container-component titulo="Nome da marca" id="inputNome" id-help="nomeHelp"
                                    texto-ajuda="Opicional. informe o nome da marca">
                                    <input type="text" class="form-control" id="inputNome" aria-describedby="nomeHelp"
                                        placeholder="Nome da marca">
                                </input-container-component>
                            </div>
                        </div>
                    </template>
                    <template v-slot:rodape>
                        <button type="submit" class="btn btn-primary btn-sm float-right">Buscar</button>
                    </template>
                </card-component>

                <!-- Inicio card de listagem  -->

                <card-component titulo="Relação de marcas">
                    <template v-slot:conteudo>
                        <table-component></table-component>
                    </template>
                    <template v-slot:rodape>
                        <button type="button" class="btn btn-primary btn-sm float-right" data-toggle="modal" data-target="#modalMarca">Adicionar</button>
                    </template>
                </card-component>

            </div>
        </div>
        <modal-component id="modalMarca" titulo="Adicionar Marca">
            <template v-slot:conteudo>
                <div class="form-group">
                    <input-container-component titulo="Nome da marca" id="novoNome" id-help="novoNomeHelp" texto-ajuda="Informe o nome da marca">
                        <input v-model="nomeMarca" type="text" class="form-control" id="novoNome" aria-describedby="novoNomeHelp" placeholder="Nome da marca">
                    </input-container-component>
                </div>
                <div class="form-group">
                    <input-container-component titulo="Imagem" id="novoImagem" id-help="novoImagemHelp" texto-ajuda="Selecione uma imagem png">
                        <input @change="carregarImagem($event)" type="file" class="form-control-file" id="novoImagem" aria-describedby="novoImagemHelp" placeholder="Selecione uma imagem">
                    </input-container-component>
                </div>
            </template>
            <template v-slot:rodape>
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Fechar</button>
                <button type="button" class="btn btn-primary" @click="salvar()">Salvar</button>
            </template>
        </modal-component>
    </div>
</template>

<script>
    export default {
        data(){
            return{
                urlBase: 'http://localhost:8000/api/v1/marca',
                nomeMarca : '',
                arquivoImagem: []
            }
        },
        computed: {
            token(){
                var token = document.cookie.split(';').find(indice => indice.startsWith('token='));
                token = token.split('=')[1];
                token = 'Bearer ' + token;
                
                return token;
            }
        },
        methods:{
            carregarImagem(event){
                this.arquivoImagem = event.target.files
            },
            salvar(){
                var formData = new FormData();
                formData.append('nome',this.nomeMarca);
                formData.append('imagem',this.arquivoImagem[0]);

                var config = {
                    headers: {
                        'Content-Type': 'multpart/form-data',
                        'Accept': 'application/json',
                        'Authorization': this.token
                    }
                }
                
                axios.post(this.urlBase,formData,config)
                .then(response => {
                    console.log(response)
                })
                .catch(errros => {
                    console.log(errros)
                })
            }
        }
    }
</script>