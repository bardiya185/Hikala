
export default function Loading() {
  return (
    <div className="max-w-[1440px] mx-auto px-4 py-6" dir="ltr">
      <div className="grid grid-cols-1 lg:grid-cols-4 gap-6">
        
        
        <aside className="lg:col-span-1 space-y-4">
          <div className="w-full h-[500px] bg-neutral-100 rounded-xl animate-pulse" />
        </aside>

    
        <main className="lg:col-span-3">
    
          <div className="h-8 bg-neutral-100 rounded w-1/3 mb-6 animate-pulse" />
          
          
          <div className="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4">
            {Array.from({ length: 8 }).map((_, index) => (
              <div 
                key={index} 
                className="w-full h-auto rounded-[20px] border border-neutral-100 bg-white p-3 animate-pulse"
              >
                <div className="rounded-[10px] w-full h-full border border-solid border-neutral-100 p-4">
                  
                  <div className="w-full aspect-[4/5] bg-neutral-200 rounded-[20px]" />
                  
                  <div className="flex justify-between items-center mt-5">
                    <div className="h-4 bg-neutral-200 rounded w-1/2" />
                    <div className="h-5 bg-neutral-200 rounded w-1/4" />
                  </div>
                  
                  <div className="flex justify-between items-center mt-4">
                    <div className="h-4 bg-neutral-200 rounded w-1/3" />
                    <div className="h-5 bg-neutral-200 rounded-md w-1/5" />
                  </div>
                </div>
              </div>
            ))}
          </div>
        </main>

      </div>
    </div>
  );
}