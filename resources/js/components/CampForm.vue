<template>
    <div class="container">
        <h2 class="mt-3">
            <a href="/home" class="btn btn-lg btn-secondary mr-3">&larr;</a>
            Dodaj nową ofertę obozu
        </h2>
        <form class="mt-5" @submit="createCamp">
            <label class="w-100">
                Nazwa:
                <input class="form-control" type="text" v-model="formData.name" required>
            </label>
            <label class="w-100 mt-3">
                Opis:
                <textarea rows="3" class="form-control" type="text" v-model="formData.description" required></textarea>
            </label>
            <div class="text-nowrap flex-wrap mt-3 d-flex justify-content-around">
                <div class="m-3">
                    <label for="price" class="m-0">
                        Cena za osobę:
                    </label>
                    <div class="input-group">
                        <input step="0.01" id="price" class="form-control" type="number" v-model="formData.price" required>
                        <span class="input-group-append">
                            <span class="input-group-text">zł</span>
                        </span>
                    </div>
                </div>
                <label class="m-3">
                    Liczba miejsc:
                    <input pattern="\d*" class="form-control" type="number" v-model="formData.available_seats" required>
                </label>
                <div class="d-inline-block d-flex flex-wrap m-3 justify-content-around">
                    <label class="mx-2">
                        Data rozpoczęcia:
                        <input class="form-control" type="date" v-model="formData.start_date" required>
                    </label>
                    <label class="mx-2">
                        Data zakończenia:
                        <input class="form-control" type="date" v-model="formData.end_date" required>
                    </label>
                </div>
            </div>
            <div class="row mt-5 pr-5">
                <div class="col-9"></div>
                <div class="col-3 text-center">
                    <input v-if="!loaderImg" type="submit" class="btn btn-lg btn-success" value="Dodaj ofertę">
                    <div v-if="loaderImg" class="lds-ring">
                        <div></div>
                        <div></div>
                        <div></div>
                        <div></div>
                    </div>
                    <div class="invalid-feedback">{{ errorMessage }}</div>
                </div>
            </div>
        </form>
    </div>
</template>

<script>
export default {
    name: "CampForm",
    data() {
        return {
            formData: {
                name: "",
                description: "",
                start_date: "",
                end_date: "",
                price: "",
                available_seats: "",
            },
            errorMessage: "",
            loaderImg: false
        }
    },
    methods: {
        createCamp(e) {
            e.preventDefault();
            // /api/camps POST
            this.loaderImg = true;
            this.errorMessage = "";

            const config = {headers: {Authorization: `Bearer ${localStorage.getItem("token")}`}};
            this.axios.post('/camps', this.formData, config)
                .then(() => {
                    document.location.href = "/home?success=1";
                }).catch((error) => {
                this.errorMessage = error.responseJSON.message;
                    console.log(error);
            });
        }
    }
}
</script>

<style scoped>
.custom-belt {
    bottom: 0;
    background-color: whitesmoke;
    border-top: 2px solid #777;
}
</style>
