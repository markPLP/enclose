class CustomMegaMenu {
  constructor(menuName) {
    // this.menuName = menuName;
    // this.apiUrl = `/wp-json/custom/v1/menu-by-name/${menuName}`;
    this.apiUrl = '/wp-json/customMegaMenu/v1/mega-menu';
    this.events();
  }

  events() {
    console.log('events');
    document.addEventListener('DOMContentLoaded', () => this.fetchMenu());
  }

  async fetchMenu() {
    try {
      const response = await fetch(this.apiUrl);
      if (!response.ok) {
        throw new Error(`Failed to fetch menu: ${response.statusText}`);
      }
      console.log('CustomMegaMenu', response);
      const data = await response.json();
      console.log('CustomMegaMenu', data);

      return data;
    } catch (error) {
      console.error('Error fetching menu:', error);
      return [];
    }
  }

  // async renderMenu(containerId) {
  //   const menuItems = await this.fetchMenu();
  //   const menuContainer = document.getElementById(containerId);

  //   if (!menuContainer) {
  //     console.error(`Error: Element with ID '${containerId}' not found.`);
  //     return;
  //   }

  //   menuContainer.innerHTML = menuItems
  //     .map((item) => `<li><a href="${item.url}">${item.title}</a></li>`)
  //     .join('');
  // }
}

export default CustomMegaMenu;
