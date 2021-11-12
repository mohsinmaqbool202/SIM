import * as actions from '../../action_types'
import * as mutations from '../../mutation_types'

import Axios from 'axios'


export default {
	[actions.ADD_PRODUCT]({commit}, payload){
		Axios.post('/products', payload)
			.then(res => {
				if(res.data.success == true) {
					window.location.href = '/products';
				}
			})
			.catch(err => {
				commit(mutations.SET_ERRORS, err.response.data.errors);
			})
	},

	[actions.EDIT_PRODUCT]({commit}, payload){
		Axios.post(`/products/${payload.id }`, payload.data)
			.then(res => {
				if(res.data.success == true) {
					window.location.href = '/products';
				}
			})
			.catch(err => {
				commit(mutations.SET_ERRORS, err.response.data.errors);
			})
	}

}