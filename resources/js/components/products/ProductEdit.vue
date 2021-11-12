<template>
  <form role="form" method="post" @submit.prevent="submitForm">
    <div class="row">
      <show-error></show-error>
      <div class="col-sm-6">
        <div class="card card-primary card-outline">
            <div class="card-body">
              <h5 class="card-title">Create Product</h5><br>
              <div class="card-body">
                <div class="form-group">
                  <label for="exampleInputEmail1">Category <span class="text-danger">*</span></label>
                  <select2 v-model="form.category_id" :options="categories" :settings="{placeholder: 'Select Category...'}"></select2>
                </div>
                <div class="form-group">
                  <label for="exampleInputEmail1">Brand <span class="text-danger">*</span></label>
                  <select2 v-model="form.brand_id" :options="brands" :settings="{placeholder: 'Select Brand...'}"></select2>
                </div>
                <div class="form-group">
                  <label>Name <span class="text-danger">*</span></label>
                  <input type="text" v-model="form.name" class="form-control" placeholder="Name">
                </div>
                <div class="form-group">
                  <label>SKU <span class="text-danger">*</span></label>
                  <input type="text" v-model="form.sku" class="form-control" placeholder="SKU">
                </div>
                <div class="form-group">
                  <label>Image <span class="text-danger">*</span></label>
                  <img class="old_image" :src="product.product_image">
                  <input type="file" @change="selectImage" class="form-control" pattern="Product image">
                </div>
                <div class="form-group">
                  <label>Cost Price($) <span class="text-danger">*</span></label>
                  <input type="text" v-model="form.cost_price" class="form-control" placeholder="Product cost price">
                </div>
                <div class="form-group">
                  <label>Retail Price($) <span class="text-danger">*</span></label>
                  <input type="text" v-model="form.retail_price" class="form-control" placeholder="Product retail price">
                </div>
                <div class="form-group">
                  <label>Year <span class="text-danger">*</span></label>
                  <input type="text" v-model="form.year" class="form-control" placeholder="Product year (Ex:2020)">
                </div>
                <div class="form-group">
                  <label>Description <span class="text-danger">*</span></label>
                  <input type="text" v-model="form.description" class="form-control" placeholder="Product description">
                </div>
                <div class="form-group">
                  <label>Status <span class="text-danger">*</span></label>
                  <select class="form-control" v-model="form.status">
                    <option value="1">Active</option>
                    <option value="0">Inactive</option>
                  </select>
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
            <div class="row mb-1" v-for="(item, index) in form.items" :key="index">
              <div class="col-sm-4">
                <select class="form-control" v-model="item.size_id">
                  <option value="">Select Size...</option>
                  <option v-for="(size, index) in sizes" :key="index" :value="size.id">{{ size.size }}</option>
                </select>
              </div>
              <div class="col-sm-3">
                <input type="text" v-model="item.location" class="form-control" placeholder="Location">
              </div>
              <div class="col-sm-3">
                <input type="number" v-model="item.quantity" class="form-control" placeholder="Quantity">
              </div>
              <div class="col-sm-2">
                <button type="button" @click="deleteItem(index)" class="btn-danger btn-sm"><i class="fa fa-trash"></i></button>
              </div>
            </div>
            <button type="button" @click="addItem" class="btn btn-primary btn-sm mt-3"><i class="fa fa-plus"></i> Add Item</button>
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
    props: ['product'],
    data() {
      return {
        form: {
          category_id: '',
          brand_id: '',
          name: '',
          sku: '',
          image: '',
          cost_price: 0,
          retail_price: 0,
          year: '',
          description: '',
          status: 1,
          items: [
            {
              size_id: '',
              location: '',
              quantity: 0
            }
          ]
        }
      }
    },
    computed: {
      ...mapGetters({
        'categories': 'getCategories',
        'brands':  'getBrands',
        'sizes': 'getSizes'
      })
    },
    mounted() {
      //get categories
      store.dispatch(actions.GET_CATEGORIES);

      //get brands
      store.dispatch(actions.GET_BRANDS);

      //get sizes
      store.dispatch(actions.GET_SIZES);

      //set product old data in form
      this.form.category_id = this.product.category_id;
      this.form.brand_id = this.product.brand_id;
      this.form.name = this.product.name;
      this.form.sku = this.product.sku;
      this.form.cost_price = this.product.cost_price;
      this.form.retail_price = this.product.retail_price;
      this.form.year = this.product.year;
      this.form.description = this.product.description;
      this.form.status = this.product.status;

      this.form.items = this.product.product_stock;

    },
    methods: {
      addItem(){
        let item = {
          size_id: '',
          location: '',
          quantity: 0
        }

        this.form.items.push(item);

      },
      deleteItem(index){
        this.form.items.splice(index);
      },
      selectImage(event) {
        this.form.image = event.target.files[0];
      },
      submitForm(){

        let data = new FormData();
        data.append('_method', 'PUT');
        data.append('category_id', this.form.category_id);
        data.append('brand_id', this.form.brand_id);
        data.append('name', this.form.name);
        data.append('sku', this.form.sku);
        data.append('image', this.form.image);
        data.append('cost_price', this.form.cost_price);
        data.append('retail_price', this.form.retail_price);
        data.append('year', this.form.year);
        data.append('description', this.form.description);
        data.append('status', this.form.status);
        data.append('items', JSON.stringify(this.form.items));

        let payload = {
        	data: data,
        	id: this.product.id
        }

        store.dispatch(actions.EDIT_PRODUCT,  payload);
      }
    }
	}
</script>

<style scoped>
	.old_image {
		width: 80px;
	}
</style>