/**
 * register_plugin for sidebar settings
 */
import { registerPlugin } from '@wordpress/plugins';
import MotorcycleSidebar from './SideBar';

/**
 * Register sidebar
 */
registerPlugin( 'ys-motorcycle-pricing-sidebar', {
	render: MotorcycleSidebar
});
