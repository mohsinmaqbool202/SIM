import * as actions from '../../action_types'
import * as mutations from '../../mutation_types'

import Axios from 'axios'


export default {
	[actions.SUBMIT_STOCK]({commit}, payload){
		Axios.post('/stocks', payload)
			.then(res => {
				if(res.data.success == true) {
					window.location.href = '/stocks';
				}
			})
			.catch(err => {
				commit(mutations.SET_ERRORS, err.response.data.errors);
			})
	}

}