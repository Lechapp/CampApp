<template>
    <div class="container text-center">
        <div v-if="camp.name" class="text-left">
            <h3 class="mt-5">Oferta obozu - {{ camp.name }}</h3>
            <div class="mt-5">
                <h5>Opis obozu:</h5>
                <p>
                    {{ camp.description }}
                </p>
            </div>
            <div class="mt-5">
                <p>
                    Start: <span class="font-weight-bold">{{ camp.start_date.split(" ")[0] }}</span> <br>
                    Koniec: <span class="font-weight-bold">{{ camp.end_date.split(" ")[0] }}</span>
                </p>
                <p>
                    Cena <span class="font-weight-bold">{{ camp.price }} zł</span>
                </p>
                <p>
                    Liczba wszystkich miejsc: <span class="font-weight-bold">{{ camp.available_seats }}</span> <br>
                    <span v-if="vacancies">
                        Liczba wolnych miejsc:
                        <span class="font-weight-bold">{{ vacancies }}</span>
                    </span>
                </p>
            </div>
            <div>

            </div>
            <camp-participants v-if="auth" @clicked="setParicipantLoader" @vacancies="setVacancies" :campID="this.campID"/>
        </div>
        <div v-if="loaderCamp || loaderParticipant" class="lds-ring">
            <div></div>
            <div></div>
            <div></div>
            <div></div>
        </div>
        <h4>{{ errorMessage }}</h4>
    </div>
</template>

<script>
import campParticipants from './CampParticipants';

export default {
    name: "CampOffer",
    components: {
        campParticipants
    },
    data() {
        return {
            loaderCamp: true,
            loaderParticipant: false,
            errorMessage: "",
            camp: {},
            vacancies: null,
            campID: 0,
            auth: false
        }
    },
    created() {
        this.campID = document.location.pathname.split("/")[2];
        this.auth = $("meta[name=login-status]").attr('content');
        this.getCamp();

    },
    methods: {
        setVacancies(occupiedSeats){
            this.vacancies = this.camp.available_seats - occupiedSeats;
        },
        setParicipantLoader(status){
            this.loaderParticipant = status;
        },
        getCamp() {
            const config = {headers: {Authorization: `Bearer ${localStorage.getItem("token")}`}};
            this.axios.get('camp/'+ this.campID, config)
                .then((response) => {

                    this.camp = JSON.parse(response.request.response);

                }).catch(() => {
                this.errorMessage = "Wystąpił błąd, spróbuj ponownie później";
            }).finally(()=>{
                this.loaderCamp = false;
            });
        }
    }
}
</script>

<style scoped>

</style>
