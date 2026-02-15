/**
 * DOM Generator Utilities
 * Centralizes HTML component creation for consistency across modules.
 */

/**
 * Generates a WAN Input Row for Load Balancing modules.
 * @param {number} i - The index of the WAN interface (1-based).
 * @param {Object} options - Configuration options.
 * @param {boolean} [options.useRatio=false] - Whether to show the Ratio/Weight column.
 * @param {boolean} [options.useFailover=false] - Whether to show the Failover Check column.
 * @param {string} [options.ratioName='wan_speed'] - Input name for ratio (e.g., 'wan_speed' for PCC, 'wan_weight' for NTH).
 * @param {string} [options.ratioLabel='Mbps'] - Suffix label for ratio.
 * @returns {string} HTML string for the table row.
 */
export function createWanRow(i, options = {}) {
    const {
        useRatio = false,
        useFailover = false,
        ratioName = 'wan_speed',
        ratioLabel = 'Mbps'
    } = options;

    return `
        <tr class="animate-fade-in" style="animation-delay: ${i * 0.05}s">
            <td class="text-center py-2">
                 <div class="input-group input-group-sm glass-group justify-content-center flex-nowrap overflow-hidden">
                    <span class="input-group-text ps-2"><i class="bi bi-ethernet"></i></span>
                    <input type="text" class="form-control bg-transparent border-0 fw-bold shadow-none" 
                           name="wan_interface_${i}" value="ether${i}" placeholder="ether${i}" 
                           style="min-width: 120px; background: transparent !important; border: none !important; box-shadow: none !important;">
                </div>
            </td>
            <td class="text-center py-2">
                 <div class="input-group input-group-sm glass-group justify-content-center flex-nowrap overflow-hidden">
                    <span class="input-group-text ps-2"><i class="bi bi-router"></i></span>
                    <input type="text" class="form-control bg-transparent border-0 font-monospace shadow-none" 
                           name="wan_gateway_${i}" placeholder="IP or Interface" 
                           style="min-width: 150px; background: transparent !important; border: none !important; box-shadow: none !important;">
                </div>
            </td>
            <td class="text-center py-2">
                <!-- Ratio Field -->
                <div class="ratio-field ${useRatio ? '' : 'd-none'} d-inline-block w-100">
                    <div class="input-group input-group-sm glass-group justify-content-center w-100 flex-nowrap overflow-hidden">
                        <input type="number" class="form-control bg-transparent border-0 text-primary fw-bold text-end shadow-none" 
                               name="${ratioName}_${i}" value="10" min="1" 
                               style="min-width: 80px; background: transparent !important; border: none !important; box-shadow: none !important;">
                        <span class="input-group-text bg-transparent border-0 text-secondary opacity-75 small pe-2">${ratioLabel}</span>
                    </div>
                </div>
            </td>
            <td class="text-center py-2">
                <!-- Failover Check Field -->
                <div class="failover-field ${useFailover ? '' : 'd-none'} d-inline-block w-100">
                    <div class="input-group input-group-sm glass-group justify-content-center w-100 flex-nowrap overflow-hidden">
                        <span class="input-group-text bg-transparent border-0 text-danger ps-2"><i class="bi bi-activity"></i></span>
                        <input type="text" class="form-control bg-transparent border-0 text-danger font-monospace shadow-none" 
                               name="wan_check_${i}" value="${getCloudflareIP(i)}" 
                               style="min-width: 130px; background: transparent !important; border: none !important; box-shadow: none !important;">
                    </div>
                </div>
            </td>
        </tr>
    `;
}

/**
 * Returns a Cloudflare IP for failover checking based on index.
 * Only simple logical cycle for demo purposes.
 * @param {number} index 
 * @returns {string} IP Address
 */
export function getCloudflareIP(index) {
    // Cycle simpler IPs or just pattern 1.1.1.X
    return `1.1.1.${index}`;
}
