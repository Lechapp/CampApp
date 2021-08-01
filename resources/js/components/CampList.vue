<template>
    <div class="container">
        <div v-if="successAdd" class="alert alert-success" role="alert">
            Oferta została dodana pomyślnie
        </div>
        <div v-if="successDelete" class="alert alert-danger" role="alert">
            Oferta została usunięta
        </div>
        <div class="overflow-auto text-center mt-5 w-100">
            <div class="d-flex flex-wrap justify-content-between align-middle">
                <div class="camp-container" v-for="(camp, index) in camps" v-if="!camp.deleted">
                    <h3 class="title">{{ camp.name }}</h3>
                    <p class="description">{{ camp.description }}</p>
                    <h3 class="dots">. . .</h3>
                    <table class="table table-sm w-100 text-center mt-3">
                        <tr>
                            <td>Start</td>
                            <td>Koniec</td>
                        </tr>
                        <tr>
                            <td>
                                {{ camp.start_date.split(" ")[0] }}
                            </td>
                            <td>
                                {{ camp.end_date.split(" ")[0] }}
                            </td>
                        </tr>
                    </table>
                    <div>
                        Cena za osobę: <span class="font-weight-bold">{{ camp.price }} zł</span>
                    </div>
                    <div class="mt-3">
                        <button v-if="!showLoader && myCamps" @click="deleteCamp(index)" class="m-3 btn btn-sm btn-danger">Usuń</button>
                        <a :href="'camp/' + camp.id" class="m-3 btn btn-sm btn-secondary">Więcej...</a>
                    </div>
                </div>
            </div>
            <div v-if="showLoader" class="lds-ring">
                <div></div>
                <div></div>
                <div></div>
                <div></div>
            </div>
            <div class="mt-4">
                <h4 v-html="errorMessage">
                </h4>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    name: "CampList",
    data() {
        return {
            camps: [],
            showLoader: true,
            errorMessage: "",
            successAdd: false,
            successDelete: false,
            auth: false,
            myCamps: false
        }
    },
    created() {
        this.auth = $("meta[name=login-status]").attr('content');
        this.myCamps = document.location.pathname === "/home";
        //clear token after logout
        if(!this.auth)
            localStorage.clear();

        const self = this;
        if(location.href.indexOf("home?success=1") !== -1){
            this.successAdd = true;
            setTimeout(function (){
                self.successAdd = false;
            }, 7000);
        }
        this.getCamps()
    },
    methods:{
        getCamps(){
            const url = document.location.pathname === "/home" ? '/my-camps' : '/camps';
            const config = {headers: {Authorization: `Bearer ${localStorage.getItem("token")}`}};
            this.axios.get(url, config)
                .then((response) => {
                    this.showLoader = false;
                    this.camps = JSON.parse(response.request.response); //if empty returns object {}

                    if($.isEmptyObject(this.camps)){
                        if(this.auth)
                            this.errorMessage = "Dodaj swoją pierwszą ofertę obozową";
                        else
                            this.errorMessage = "Brak ofert obozowych<br/><a href='/create-camp'>Dodaj pierwszą!</a>";
                    }

                }).catch((error) => {
                console.log(error);
                this.showLoader = false;
                this.errorMessage = "Wystąpił błąd, spróbuj ponownie później";
            });
        },
        deleteCamp(campIndex){
            this.errorMessage = "";
            if(!confirm("Czy na pewno chcesz usunąć obóz " + this.camps[campIndex].name + "?")){
                return false;
            }

            const config = {headers: {Authorization: `Bearer ${localStorage.getItem("token")}`}};
            this.axios.delete('/camps/' + this.camps[campIndex].id, config)
                .then(() => {

                    this.showLoader = false;
                    this.camps[campIndex].deleted = true;
                    this.successDelete = true;
                    const self = this;
                    setTimeout(()=>{
                        self.successDelete = false;
                    }, 7000);

                }).catch(() => {
                this.showLoader = false;
                this.errorMessage = "Wystąpił błąd, spróbuj ponownie później";
            });
        }
    }
}
</script>

<style scoped>
.camp-container{
    margin: 1em;
    width: 290px;
    border-radius: 4px;
    border: 2px solid #3f8fd2;
    height: 400px;
    padding: 2em;
}

.description{
    margin-top: 2em;
    overflow: hidden;
    margin-bottom: 0;
    max-height: 50px;
}
.dots{
    margin-top: -10px;
}
.title{
    height: 60px;
    overflow: hidden;
}
</style>
