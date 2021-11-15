<template>
  <form role="form" method="post" @submit.prevent="submitForm">
    <div class="row">
      <show-error></show-error>
      <div class="col-sm-6">
        <div class="card card-primary card-outline">
            <div class="card-body">
              <h5 class="card-title">Return Product</h5><br>
              <div class="card-body">
                <div class="form-group">
                  <label for="exampleInputEmail1">Product <span class="text-danger">*</span></label>
                  <select2 @change="selectedProduct" v-model="form.product_id" :options="products" :settings="{placeholder: 'Select Product...'}"></select2>
                </div>
                <div class="form-group">
                  <label>Date <span class="text-danger">*</span></label>
                  <input type="date" class="form-control" v-model="form.date">
                </div>
              </div>
              <div class="card-footer">
                <button type="submit" class="btn btn-primary btn-sm"><i class="fa fa-plus"></i> Submit</button>
              </div>
            </div>
        </div>
      </div>
      <div class="col-sm-6">
        <div class="card card-primary card-outline">
          <div class="card-body">
            <h5 class="card-title">Product Size</h5><br>
            <table class="table table-sm">
              <tr v-for="(item, index) in form.items" :key="index">
                <td>{{ item.size }}</td>
                <td>
                  <input class="form-control" v-model="item.quantity" placeholder="Quantity">
                </td>
              </tr>
            </table>
          </div>
        </div>
      </div>
    </div>
  </form>
</template>

<script>
  import store from '../../store';
  import * as actions from '../../store/action_types';
  import {mapGetters} from 'vuex';
  import Select2 from 'v-select2-component';
  import ShowError from '../utils/ShowError';

	export default {
    components: {
      Select2,
      ShowError
    },
    data() {
      return {
        form: {
          date: '',
          product_id: '',
          items: [
            
          ]
        }
      }
    },
    computed: {
      ...mapGetters({
        'products': 'getProducts',
      })
    },
    mounted() {
      //get products
      store.dispatch(actions.GET_PRODUCTS );
    },
    methods: {
      selectedProduct(id) {
        this.form.items = [];
        let product = this.products.filter(product => product.id == id);

        product[0].product_stock.map(stock => {
          let item = {
            size_id: stock.size_id,
            size: stock.size.size,
            quantity: ''
          }
          this.form.items.push(item);
        })
      },
      submitForm() {
        store.dispatch(actions.SUBMIT_RETURN_PRODUCT, this.form);
      }
    }
	}
</script>