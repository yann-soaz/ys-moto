/**
 * register_plugin for sidebar settings
 */
import { registerPlugin } from '@wordpress/plugins';
import MotorcycleSidebar from './SideBar';

/**
 * Register sidebar
 */
registerPlugin( 'ys-motorcycle-metadata-sidebar', {
	render: MotorcycleSidebar
});
