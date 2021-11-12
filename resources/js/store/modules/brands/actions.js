import * as actions from '../../action_types'
import * as mutations from '../../mutation_types'

import Axios from 'axios'

export default {
	[actions.GET_BRANDS]({ commit }){
		Axios.get('/api/brands')
			.then(res=>{
				if(res.data.success == true) {
					commit(mutations.SET_BRANDS, res.data.data);
				}
			})
			.catch(err=> {
				console.log(err.response);
			})
	}
}