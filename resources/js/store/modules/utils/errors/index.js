import actions from './actions.js'
import getters from './getters.js'
import mutations from './mutations.js'

const state = {
	is_errors: false,
	errors: []
}

export default {
	state,
	actions,
	getters,
	mutations
}