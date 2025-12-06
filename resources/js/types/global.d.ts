import { PageProps as InertiaPageProps } from '@inertiajs/vue3';

declare module '@inertiajs/vue3' {
  interface PageProps extends InertiaPageProps {
    flash: { success: string; error: string };
    user: {
      id: number;
      first_name: string;
      last_name: string;
      email: string;
      phone_number: string;
      full_name: string;
      shopper: {
        address: string | null;
        city: string | null;
        county: string | null;
        town: string | null;
        closest_landmark: string | null;
      } | null;
    } | null;
    order_id?: number;
    auth?: {
      user: { // Assuming 'auth' prop always has a 'user' property
        id: number;
        first_name: string;
        last_name: string;
        email: string;
        phone_number: string;
        full_name: string;
        shopper: {
          address: string | null;
          city: string | null;
          county: string | null;
          town: string | null;
          closest_landmark: string | null;
        } | null;
      } | null;
    };
    // Add any other global props that are always present
    // For example:
    // name: string;
    // quote: any; // Or define a proper interface for quote
    // sidebarOpen: boolean;
  }
}

// To make the `route` function globally available to TypeScript
declare function route(name: string, params?: any): string;
