
"use client";

import React, { useState } from "react";
import {
  QueryClient,
  QueryClientProvider,
} from "@tanstack/react-query";

function TanstackQueryProvider({ children }) {
  const [queryClient] = useState(() => {
    return new QueryClient({
      defaultOptions: {
        queries: {
          staleTime: 60000,
          refetchOnWindowFocus: false,
          retry: 1,
        },

        mutations: {
          retry: 0,
        },
      },
    });
  });

  return (
    <QueryClientProvider client={queryClient}>
      {children}
    </QueryClientProvider>
  );
}

export default TanstackQueryProvider;
