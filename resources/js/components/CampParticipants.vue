<template>
    <div v-if="owner" class="container mt-5 text-center">
        <div v-if="successDelete" class="alert alert-success" role="alert">
            Uczestnik został usunięty
        </div>
        <div class="text-left" v-if="!errorMessage">
            <h5>Lista uczestników</h5>
            <table class="table table-sm mt-4">
                <tbody>
                <tr v-for="(participant, index) in participants" v-if="!participant.deleted">
                    <td>{{ participant.name }} {{ participant.surname }}</td>
                    <td>
                        <button @click="deleteParticipant(index)" class="ml-3 btn btn-sm btn-danger">x</button>
                    </td>
                </tr>
                <tr v-if="participants.length === 0">
                    <td class="text-center">Dodaj pierwszego uczestnika obozu</td>
                </tr>
                </tbody>
            </table>
        </div>
        <div>{{ errorMessage }}</div>
        <form @submit="addParticipant" class="mt-5 text-left">

            <label class="m-2">
                Imię
                <input v-model="formData.name" placeholder="Imię" type="text" maxlength="50" required
                       class="form-control">
            </label>
            <label class="m-2">
                Nazwisko
                <input v-model="formData.surname" placeholder="Nazwisko" maxlength="50" type="text" required
                       class="form-control">
            </label>
            <div v-if="loaderImage" class="d-inline-block">
                <div class="lds-ring">
                    <div></div>
                    <div></div>
                    <div></div>
                    <div></div>
                </div>
            </div>
            <div>
                {{ addErrorMessage }}
            </div>
            <input v-if="!loaderImage" type="submit" class="btn btn-success ml-3" value="Dodaj">
        </form>
    </div>
</template>

<script>
export default {
    name: "CampParticipants",
    props: ['campID'],
    data() {
        return {
            participants: [],
            loaderImage: false,
            auth: false,
            owner: false,
            errorMessage: "",
            addErrorMessage: "",
            successDelete: false,
            formData: {
                name: "",
                surname: "",
                camp_id: this.campID
            }
        }
    },
    created() {
        this.auth = $("meta[name=login-status]").attr('content');
        if (this.auth) {
            this.$emit('setLoader', true);
            this.getParticipant()
        }
    },
    methods: {
        addParticipant(e) {
            e.preventDefault();
            this.loaderImage = true;
            this.addErrorMessage = "";
            const config = {headers: {Authorization: `Bearer ${localStorage.getItem("token")}`}};
            this.axios.post('/participants', this.formData, config)
                .then(() => {
                    this.participants.push({
                        name: this.formData.name,
                        surname: this.formData.surname
                    });
                    this.$emit("vacancies", this.participants.length);
                    this.formData.name = "";
                    this.formData.surname = "";
                }).catch((error) => {
                this.addErrorMessage = error.response.status === 409 ? "Taki uczestnik już istnieje" : "Wystąpił błąd, spróbuj ponownie";
            }).finally(() => {
                this.loaderImage = false;
            });
        },
        getParticipant() {
            const config = {headers: {Authorization: `Bearer ${localStorage.getItem("token")}`}};
            this.axios.get('/camp/' + this.campID + '/participants', config)
                .then((response) => {

                    const data = JSON.parse(response.request.response);
                    this.participants = data['participants'];
                    this.owner = data['owner'];
                    if(this.owner)
                        this.$emit("vacancies", this.participants.length);
                }).catch(() => {
                this.errorMessage = "Wystąpił błąd przy pobieraniu uczestników obozu, spróbuj ponownie później";
            }).finally(() => {
                this.$emit('setLoader', false);
            });
        },
        deleteParticipant(index) {
            if (!confirm("Czy na pewno chcesz usunąć uczestnika " + this.participants[index].name + " " + this.participants[index].surname + "?")) {
                return false;
            }
            this.loaderImage = true;

            const config = {headers: {Authorization: `Bearer ${localStorage.getItem("token")}`}};
            this.axios.delete('/participants/' + this.participants[index].id, config)
                .then(() => {
                    this.participants[index].deleted = true;
                    this.successDelete = true;
                    const self = this;
                    setTimeout(() => {
                        self.successDelete = false;
                    }, 7000);

                }).catch(() => {
                this.addErrorMessage = "Wystąpił błąd, spróbuj ponownie później";
            }).finally(() => {
                this.loaderImage = false;
            });
        }
    }
}
</script>

<style scoped>

</style>
