<script>
    import axios from "axios";

    export default {
        data() {
            return {
                api_url: "http://127.0.0.1:8000/price",
                vehicle_types: ['common', 'luxury'],
                vehicle_type: null,
                price: 0,
                fees: null,
                error_message: null
            }
        },

        mounted() {
            this.vehicle_type = this.vehicle_types[0];
            this.$watch(vm => [vm.vehicle_type, vm.price], val => {
                this.formatPrice();
                this.checkPrice();
            });
        },

        methods: {
            fetchFees() {
                return axios.post(this.api_url, {
                    'vehicleType': this.vehicle_type,
                    'price': this.price
                }, {
                    headers: {"Content-Type": "application/x-www-form-urlencoded"}
                });
            },

            processResponse(response) {
                const response_data = response['data'];

                if (response_data.hasOwnProperty('error')) {
                    this.error_message = response['eror'];
                    this.fees = null;
                } else {
                    this.error_message = null;
                    this.fees = response_data['result'];
                }
            },

            checkPrice() {
                this.error_message = null;
                this.fees = null;

                const errors = this.checkInputs();

                if (errors) {
                    this.error_message = errors;

                    return;
                }


                this.fetchFees()
                .then(
                    response => this.processResponse(response)
                ).catch(
                    () => this.error_message = 'Server error'
                );
            },

            checkInputs() {
                let errors = null;

                if (isNaN(this.price) || Number(this.price) <= 0) {
                    errors = 'Not a valid price';
                }

                return errors;
            },

            formatPrice() {
                this.price = Math.round(this.price*100)/100;
            },

            capitalize(val) {
                return val[0].toUpperCase() + val.slice(1);
            }
        }
    }
</script>


<template>
    <div class="d-flex flex-row justify-content-center mb-2">
        <h2>Check the Price</h2>
    </div>
    <div class="row mb-2">
        <div class="row mb-2">
            <div class="d-flex flex-row justify-content-center gap-3">
                <div><span>Car type: </span></div>
                <div>
                    <select class="form-select" aria-label="Default select example" v-model="vehicle_type">
                        <option v-for="type in vehicle_types" :value="type">{{ capitalize(type) }}</option>
                    </select>
                </div>
            </div>
        </div>
        <div class="d-flex flex-row justify-content-center mb-2">
            <div><span>Bid price: $<input type="number" min="0" v-model="price"/></span></div>
        </div>
    </div>
    <div class="row alert alert-danger" v-if="error_message" role="alert">
        <span>{{ error_message }}</span>
    </div>
    <div class="row" v-if="fees">
        <table class="table">
            <tbody>
                <tr v-for="(fee, type) in fees['fees']" :key="type">
                    <td>{{ capitalize(type) }}</td> 
                    <td>{{ fee }}</td>  
                </tr>
                <tr>
                    <td><strong>Total</strong></td> 
                    <td>{{ fees.total }}</td>  
                </tr>
            </tbody>
        </table>
    </div>
</template>
