import * as mutations from '../../mutation_types'

export default {
	[mutations.SET_PRODUCTS](state, payload){
		state.products = payload;
	}
}